<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdFormRequest;
use App\Http\Requests\IdRequest;
use App\Jobs\EnviarEmailAtualizacaoJob;
use App\Models\Clientes;
use App\Models\Contas;
use App\Models\Controle;
use App\Models\Servicos;
use App\Models\TemplatesEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ControleController extends Controller
{

    const PATH_VIEW = 'pages/controle/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $servicos = Servicos::whereNotIn('se_status', ['fechada', 'desligada'])
            ->whereNull('se_limite')
            ->orWhereColumn('se_qtd_assinantes', '<', 'se_limite')
            ->get();

        $template = TemplatesEmail::all();

        return view(self::PATH_VIEW.'index', compact('servicos', 'template'));
    }

    public function trocarConta(Request $request)
    {

        $data = $request->validate([
            'cs_id' => 'required|int',
            'cs_servico_id' => 'required|int',
            'cs_cliente_id' => 'required|int',
        ]);

        try {

            DB::beginTransaction();

            $con = Controle::find($data['cs_id']);
            $con->fill($data);
            $con->save();

            DB::commit();

            EnviarEmailAtualizacaoJob::dispatch($data['cs_servico_id'], $data['cs_cliente_id'])->afterCommit();

            return back()->with('success', $this->msgSuccess);

        }catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao salvar registro: '.$e->getMessage());
            return back()->with('error', $this->msgError.$e->getMessage())->withInput();
        }

    }

    public function addEmailAdicional(Request $request)
    {

        $data = $request->validate([
            'cs_id' => 'required|int',
            'cs_template_email_id' => 'required|int',
        ]);

        try {

            DB::beginTransaction();
                Controle::where('cs_id', $data['cs_id'])->update($data);
            DB::commit();
            return back()->with('success', $this->msgSuccess);
        }catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao salvar registro: '.$e->getMessage());
            return back()->with('error', $this->msgError.$e->getMessage())->withInput();
        }

    }

    public function ajaxBanCliente(Request $request, IdRequest $idRequest)
    {

        if(!$request->ajax()){
            return false;
        }

        $data = $request->validate([
            'idReg' => 'required|int',
        ]);

        try {

            $servico = Controle::with('cliente')->find($request->idReg);
            if(!$servico){
                return response()->json(['ok' => false]);
            }

            $up['cl_banido'] = 1;
            $servico->cliente()->update($up);

            Controle::where('cs_cliente_id', $servico->cliente->cl_id)->update(['cs_status' => 'suspenso']);

            return response()->json(['ok' => 1]);

        }catch (\Exception $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()]);
        }

    }

    public function ajaxBanClienteServico(Request $request, IdRequest $idRequest)
    {

        if(!$request->ajax()){
            return false;
        }

        $data = $request->validate([
            'idReg' => 'required|int',
        ]);

        try {

            $servico = Controle::with('cliente')->find($request->idReg);
            if(!$servico){
                return response()->json(['ok' => false]);
            }

            $up = 'suspenso';

            if($servico->cs_status == 'suspenso') {
                $up = 'ativo';
                $servico->cliente()->update(['cl_banido' => 0]);
            }

            $servico->cs_status = $up;
            $servico->save();

            return response()->json(['ok' => 1]);

        }catch (\Exception $e) {
            return response()->json(['ok' => false]);
        }

    }

    public function getDatatables(Request $request)
    {
        if(!$request->ajax()){
            return false;
        }

        $data = Controle::with(['cliente', 'servico', 'pedido', 'templateEmail']);

        // Filtro por pesquisa
        if ($request->filled('busca')){

            $busca = $request->busca;

            $data->where(function ($query) use ($busca) {

                // A. Busca dentro da tabela de CLIENTES
                $query->whereHas('cliente', function ($q) use ($busca) {
                    $q->where('cl_nome', 'like', "%{$busca}%")
                    ->orWhere('cl_email', 'like', "%{$busca}%")
                    ->orWhere('cl_email_envio', 'like', "%{$busca}%");
                })

                // B. OU Busca dentro da tabela de SERVIÇOS
                ->orWhereHas('servico', function ($q) use ($busca) {
                    $q->where('se_nome', 'like', "%{$busca}%")
                        ->orWhere('se_cod', 'like', "%{$busca}%") // Ajuste o nome da coluna do código
                        ->orWhere('se_username', 'like', "%{$busca}%"); // Ajuste o nome da coluna do código
                });

                // C. OU Busca na própria tabela pivot (Ex: código de rastreio/status)
                //->orWhere('cs_status', 'like', "%{$busca}%");
            });
        }

        $data->orderBy('cs_id', 'desc');

        $dataTable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('servico', function($row) {
                $html = '<div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        '.$row->servico->conta->co_codigo.'
                    </div>
                    <h6 class="mb-0 fw-semibold">'.$row->servico->se_nome.'</h6>
                </div>';
                return $html;
            })
            ->addColumn('codigo', function($row) {
                $html = '<span class="badge bg-light text-dark border font-monospace">'.$row->servico->se_cod.'</span>';
                return $html;
            })
            ->addColumn('email_envio', function($row) {

                $email_envio = ($row->cliente->cl_email_envio) ?: $row->cliente->cl_email;

                $html = '<div class="small text-muted">
                            <i class="fa-regular fa-user text-muted me-1"></i>
                            '.$row->cliente->cl_nome.'
                         </div>';
                $html.='<div class="fw-medium text-primary mb-1"><i class="fa-regular fa-envelope text-primary me-1"></i>'
                            .$email_envio.'
                        </div>';

                return $html;
            })
            ->addColumn('username', function($row) {
                return '<code>'.$row->servico->se_username.'</code>';
            })
            ->addColumn('senha', function($row) {

                $html = '<div class="input-group input-group-sm" style="width: 130px;">
                            <input type="text" class="form-control border-end-0 bg-white" value="'.$row->servico->se_senha_atual.'" readonly>
                            <button class="btn btn-light border border-start-0 text-muted" type="button" title="Copiar"><i class="fa-regular fa-copy"></i></button>
                        </div>';

                if($row->servico->se_status == 'desligada'){
                    $html = '<div class="input-group input-group-sm" style="width: 130px;">
                                <input type="text" class="form-control border-end-0 bg-light" value="'.$row->servico->se_senha_atual.'" readonly disabled>
                            </div>';
                }

                return $html;
            })
            ->addColumn('dt_renovacao', function($row) {

                if ($row->cs_data_termino) {

                    $hoje = Carbon::today();
                    $renovacao = Carbon::parse($row->cs_data_termino)->startOfDay();

                    if ($renovacao->greaterThan($hoje)) {

                        // 1. A CORREÇÃO DO PONTO DE PARTIDA
                        if (!empty($row->cs_data_inicio)) {
                            $inicio = Carbon::parse($row->cs_data_inicio)->startOfDay();
                        } else {
                            // Último recurso: Assume que é um plano mensal e subtrai 30 dias do vencimento
                            $inicio = (clone $renovacao)->subDays(30);
                        }

                        // 2. A MATEMÁTICA CORRETA DOS DIAS
                        $totalDias = $inicio->diffInDays($renovacao);
                        $diasPassados = $inicio->diffInDays($hoje);
                        $diasRestantes = $hoje->diffInDays($renovacao);

                        if ($totalDias > 0) {
                            // AQUI ESTÁ O SEGREDO DO ZERO:
                            // Se começou hoje, $diasPassados é 0, logo $percentual é 0.
                            $percentualBruto = round(($diasPassados / $totalDias) * 100);

                            // Trava entre 0 e 100
                            $percentual = max(0, min(100, $percentualBruto));
                        } else {
                            $percentual = 100;
                        }

                        // CORES
                        if ($percentual < 50) {
                            $progressClass = 'bg-success';
                        } elseif ($percentual < 80) {
                            $progressClass = 'bg-warning';
                        } else {
                            $progressClass = 'bg-danger';
                        }

                        $textClass = 'text-dark';
                        $comp = '<small class="fw-medium text-muted d-block mt-1">'.$diasRestantes.' dias restantes</small>';
                        $textIconClass = 'text-muted';
                        $data = $renovacao->format('d/m/Y');
                    } else {
                        // Já expirado
                        $progressClass = 'bg-danger';
                        $textClass = 'text-danger';
                        $comp = '<small class="text-danger fw-bold d-block mt-1">Expirado</small>';
                        $textIconClass = 'text-danger';
                        $data = $renovacao->format('d/m/Y');
                        $percentual = 100;
                    }
                } else {
                    // Vitalício
                    $progressClass = 'bg-success';
                    $textClass = 'text-dark';
                    $comp = '<small class="fw-medium text-muted d-block mt-1">Vitalício</small>';
                    $textIconClass = 'text-muted';
                    $data = 'Vitalício';
                    $percentual = 100;
                }

                $html = '<div class="fw-semibold '.$textClass.' mb-1">
                            <i class="fa-regular fa-calendar '.$textIconClass.' me-1"></i> '.$data.'
                        </div>
                        <div class="progress" style="height: 5px; width: 100px;">
                            <div class="progress-bar '.$progressClass.'" role="progressbar" style="width: '.$percentual.'%" aria-valuenow="'.$percentual.'" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>'.$comp;

                if($row->servico->se_status == 'fechada'){
                    $html = '<div class="fw-semibold text-secondary mb-1"><iclass="fa-regular fa-calendar text-muted me-1"></i> '.$row->cs_data_termino->format('d/m/Y').'</div>';
                }elseif($row->servico->se_status == 'desligada'){
                    $html = '<div class="fw-semibold text-secondary mb-1"><iclass="fa-regular fa-calendar text-muted me-1"></i> Desligada</div>';
                }

                return $html;
            })
            ->addColumn('qtd_update', function($row) {
                return 0;
            })
            ->addColumn('status', function($row) {
                //
                switch ($row->cs_status) {
                    case 'suspenso':
                        $html = '<span class="badge bg-opacity-25 text-bg-warning border border-warning border-opacity-50 rounded-pill px-3">Suspenso</span>';
                    break;
                    case 'expirado':
                        $html = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3">Expirado</span>';
                        break;
                    default:
                        $html = '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Ativo</span>';

                }

                return $html;
            })
            ->addColumn('emailad', function($row) {
                //
                $codigo = $row->templateEmail->te_codigo ?? '--';
                return '<div class="text-muted small fst-italic">'.$codigo.'</div>';
            })
            ->addColumn('obs', function($row) {
                //
                return '<div class="text-muted small fst-italic">'.$row->cs_obs.'</div>';
            })
            ->addColumn('acoes', function($row) {

                $btn = '<div class="d-flex align-items-center justify-content-end">';

                if(!$row->cliente->cl_banido) {

                    $btn.= '<button data-id="' . $row->cs_id . '" data-id-cliente="' . $row->cliente->cl_id . '" class="btn btn-sm btn-danger btnBanirCliente" title="Bloquear Cliente e todos os serviços" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Banir Cliente de todos os serviços dele"><i class="fa-solid fa-user-large-slash"></i></button>';

                    if($row->cs_status == 'ativo') {
                        $btn.= '<button data-id="' . $row->cs_id . '" class="btn btn-icon btn-info btn-sm ms-1 btnEmailAdicional" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Registrar email adicional"><i class="fa-solid fa-envelope"></i></button>';
                        $btn .= '<button data-id-cliente="' . $row->cliente->cl_id . '" data-cliente="' . $row->cliente->cl_nome . '" data-id="' . $row->cs_id . '" class="btn btn-sm btn-light border text-dark ms-1 btnTrocarConta" title="Mudar de Conta" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Mudar de serviço"><i class="fa-solid fa-repeat"></i></button>';
                        $btn.= '<button data-id="' . $row->cs_id . '" class="btn btn-icon btn-warning btn-sm ms-1 btnSuspender" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Suspender cliente neste Serviço"><i class="fa-solid fa-ban"></i></button>';
                    }elseif($row->cs_status == 'suspenso'){
                        $btn.= '<button data-id="'.$row->cs_id.'" class="btn btn-icon btn-success btn-sm ms-1 btnLiberar" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Desbanir cliente neste Serviço"><i class="fa-solid fa-chalkboard-user"></i></button>';
                    }
                }else{
                    $btn.= '<button class="btn btn-sm btn-danger disabled"><i class="fa-solid fa-user-xmark"></i></button>';
                }

                $btn.= '<a href="javascript:;" data-id="'.Str::toBase64($row->cs_id).'" class="btn btn-icon btn-danger btn-sm ms-1 btdelete" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Excluir permanentemente"><i class="fas fa-trash" aria-hidden="true"></i></a>';

                $btn.= '</div>';

                return $btn;
            })

            //Força a coluna a ser gerada com HTML
            ->rawColumns(['servico','email_envio','codigo','username','senha','acoes','status','dt_renovacao','obs', 'emailad'])
            ->make(true);

        return $dataTable;
    }

    public function ajaxDestroy(Request $request, Controle $controle)
    {

        if(!$request->ajax()){
            return false;
        }

        $validated = $request->validate([
            'idReg' => 'required|string',
        ]);

        $resp['ok'] = false;
        $resp['permissao'] = false;
        $resp['st'] = false;

        $idReg = Str::fromBase64($validated['idReg']);
        $op = Controle::find($idReg);

        if($op->delete()){
            $resp['ok'] = true;
            $resp['permissao'] = true;
        }

        return response()->json($resp);

    }

}
