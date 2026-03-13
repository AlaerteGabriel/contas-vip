<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdFormRequest;
use App\Models\Clientes;
use App\Models\Contas;
use App\Models\Controle;
use App\Models\Pedidos;
use App\Models\Servicos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ServicosController extends Controller
{

    const PATH_VIEW = 'pages/servicos/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicos = Servicos::all();
        $contas = Contas::all();
        return view(self::PATH_VIEW.'index', compact('servicos', 'contas'));
    }

    public function create()
    {

    }

    public function edit(int $id, IdFormRequest $request)
    {
        if(!$id){
            return back()->with('error', 'Regitro não encontrado')->withInput();
        }

        $servico = Servicos::find($id);
        return view(self::PATH_VIEW.'edit', compact('servico'));
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'se_contas_id' => 'required|int',
            'se_email_vinculado' => 'nullable|email',
            'se_username' => 'nullable|string',
            'se_senha_atual' => 'required|string',
            'se_data_renovacao' => 'nullable|date',
            'se_status' => 'required|string|in:ativa,fechada,desligada,davez',
            'se_data_ult_assinatura' => 'nullable|date',
            'se_qtd_assinantes' => 'nullable|int',
            'se_limite' => 'nullable|int',
            'se_tipo' => 'required|string|in:Free,Premium',
        ]);

        try {

            DB::beginTransaction();

            $contas = Contas::find($data['se_contas_id']);
            if (!$contas) {
                return back()->with('error', 'Registro de conta não encontrado')->withInput();
            }

            $data['se_nome'] = $contas->co_nome;
            $servico = Servicos::create($data);
            $servico->se_cod = $contas->co_codigo . $servico->se_id;
            $servico->save();

            DB::commit();

            return back()->with('success', $this->msgSuccess);

        }catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao salvar registro: '.$e->getMessage());
            return back()->with('error', $this->msgError)->withInput();
        }

    }

    public function update(Request $request)
    {

        $data = $request->validate([
            'se_id' => 'required|int',
            'se_email_vinculado' => 'nullable|email',
            'se_username' => 'nullable|string',
            'se_senha_atual' => 'required|string',
            'se_data_renovacao' => 'nullable|date',
            'se_status' => 'required|string|in:ativa,fechada,desligada,davez',
            'se_data_ult_assinatura' => 'nullable|date',
            'se_qtd_assinantes' => 'nullable|int',
            'se_limite' => 'nullable|int',
            'se_tipo' => 'required|string|in:Free,Premium',
        ]);

        try {

            DB::beginTransaction();

            // 1. Instancia o model (carrega os dados para a memória)
            $servico = Servicos::where('se_id', $data['se_id'])->firstOrFail();
            // 2. Atualiza via Eloquent (Isto dispara os eventos e aciona o Observer!)
            $servico->update($data);

            DB::commit();

            return back()->with('success', $this->msgSuccess);

        }catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao salvar registro: '.$e->getMessage());
            return back()->with('error', $this->msgError.$e->getMessage())->withInput();
        }

    }

    public function altSenha(Request $request)
    {

        $data = $request->validate([
            'se_id' => 'required|string',
            'se_senha_atual' => 'required|string',
        ]);

        $idReg = Str::fromBase64($data['se_id']);

        DB::beginTransaction();

        try {

            // 1. Instancia o model (carrega os dados para a memória)
            $servico = Servicos::where('se_id', $idReg)->firstOrFail();
            // 2. Atualiza via Eloquent (Isto dispara os eventos e aciona o Observer!)
            $servico->se_senha_atual = $data['se_senha_atual'];
            $servico->save();

            DB::commit();

            return back()->with('success', $this->msgSuccess);

        }catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao salvar registro: '.$e->getMessage());
            return back()->with('error', $this->msgError.$e->getMessage())->withInput();
        }

    }

    public function getDatatables(Request $request)
    {
        if(!$request->ajax()){
            return false;
        }

        $data = Servicos::query();

        // Filtro por pesquisa
        if ($request->filled('busca')){
            $data->where(function ($query) use ($request) {
                $query->where('se_nome', 'like', "%{$request->busca}%");
                $query->orWhere('se_email_vinculado', 'like', "%{$request->busca}%");
                $query->orWhere('se_cod', 'like', "%{$request->busca}%");
            });
        }

        $data->orderBy('se_id', 'desc');

        $dataTable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('codigo', function($row) {
                $html = '<span class="badge bg-light text-dark border font-monospace">'.$row->se_cod.'</span>';
                return $html;
            })
            ->addColumn('nome', function($row) {
                $html = '<h6 class="mb-0 fw-semibold text-dark">'.$row->se_nome.'</h6>';
                return $html;
            })
            ->addColumn('email', function($row) {
                return '<div class="fw-medium text-primary mb-1"><i class="fa-regular fa-envelope me-1"></i>'.$row->se_email_vinculado.'</div>';
            })
            ->addColumn('username', function($row) {
                return '<div class="small text-muted"><i class="fa-regular fa-user text-muted me-1"></i>'.$row->se_username.'</div>';
            })
            ->addColumn('senha', function($row) {

                $html = '<div class="input-group input-group-sm" style="width: 130px;">
                            <input type="text" class="form-control border-end-0 bg-white" value="'.$row->se_senha_atual.'" readonly>
                            <button class="btn btn-light border border-start-0 text-muted" type="button" title="Copiar"><i class="fa-regular fa-copy"></i></button>
                        </div>';

                if($row->se_status == 'desligada'){
                    $html = '<div class="input-group input-group-sm" style="width: 130px;">
                                <input type="text" class="form-control border-end-0 bg-light" value="'.$row->se_senha_atual.'" readonly disabled>
                            </div>';
                }

                return $html;
            })
            ->addColumn('dt_renovacao', function($row) {

                if ($row->se_data_renovacao) {

                    $hoje = Carbon::today();
                    $renovacao = Carbon::parse($row->se_data_renovacao)->startOfDay();

                    if ($renovacao->greaterThan($hoje)) {

                        $inicio = $hoje;

                        // Passar 'false' como segundo parâmetro permite receber valores negativos caso as datas estejam invertidas
                        $totalDias = $inicio->diffInDays($renovacao, false);
                        $diasPassados = $inicio->diffInDays($hoje, false);
                        $diasRestantes = $hoje->diffInDays($renovacao, false);

                        if ($totalDias > 0) {
                            // max(0, ...) impede que dias passados sejam negativos
                            $percentualBruto = round((max(0, $diasPassados) / $totalDias) * 100);
                            // min(100, ...) trava a barra no máximo de 100%
                            $percentual = min(100, $percentualBruto);
                        } else {
                            $percentual = 100;
                        }

                        // Definir cor conforme percentual
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

                if($row->se_status == 'desligada'){
                    $html = '<div class="fw-semibold text-secondary mb-1"><iclass="fa-regular fa-calendar text-muted me-1"></i> Desligada</div>';
                }

                return $html;
            })
            ->addColumn('tipo', function($row) {
                return $row->se_tipo;
            })
            ->addColumn('dt_update', function($row) {
                $comp = false;
                $dataUpdate = ($row->se_data_update) ? $row->se_data_update->format('d/m/Y') : '--';

                if($row->se_data_update && Carbon::parse($row->se_data_update)->isToday()) {
                    $comp = '<small class="fw-medium text-muted d-block mt-1">HOJE!</small>';
                }

                $html = '<div class="fw-semibold text-dark mb-1">
                            <i class="fa-regular fa-calendar text-dark me-1"></i> '.$dataUpdate.'
                        </div>'.$comp;

                return $html;
            })
            ->addColumn('qtdAssinantes', function($row) {
                $limite = ($row->se_limite) ?: '--';
                return ($row->se_limite == $row->se_qtd_assinantes) ? '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3">'.$row->se_qtd_assinantes.'/'.$limite.'</span>' : $row->se_qtd_assinantes.'/'.$limite;
            })
            ->addColumn('limite', function($row) {
                $limite = ($row->se_limite) ?: '--';
                return '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3">'.$limite.'</span>';
            })
            ->addColumn('status', function($row) {
                //
                switch ($row->se_status) {
                    case 'fechada':
                        $html = '<span class="badge bg-opacity-25 text-bg-warning border border-warning border-opacity-50 rounded-pill px-3">Fechada</span>';
                    break;
                    case 'desligada':
                        $html = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3">Desligada</span>';
                        break;
//                    case 'banida':
//                        $html = '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3">Suspenso</span>';
//                    break;
                    case 'davez':
                        $html = '<span class="badge bg-dark bg-opacity-10 text-dark border border-dark border-opacity-25 rounded-pill px-3">Prioridade</span>';
                    break;
                    default:
                        $html = '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Ativo</span>';

                }

                return $html;
            })
            ->addColumn('acoes', function($row) {

                $btn = '<div class="d-flex align-items-center justify-content-end">';

                $btn.= '<a href="javascript:;" data-senha="'.$row->se_senha_atual.'" data-status="'.$row->se_status.'" data-id="'.Str::toBase64($row->se_id).'" class="btn btn-icon btn-dark btn-sm ms-1 btnaltSenha" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Alterar Senha e atualizar"><i class="fas fa-key" aria-hidden="true"></i></a>';
                $btn.= '<a href="'.route('dashboard.servicos.edit', $row->se_id).'" class="btn btn-icon btn-sm btn-light border text-primary ms-1" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Editar"><i class="fa-solid fa-pen"></i></a>';
                $btn.= '<a href="javascript:;" data-id="'.Str::toBase64($row->se_id).'" class="btn btn-icon btn-danger btn-sm ms-1 btdelete" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Excluir permanentemente"><i class="fas fa-trash" aria-hidden="true"></i></a>';
                $btn.= '</div>';

                return $btn;
            })

            //Força a coluna a ser gerada com HTML
            ->rawColumns(['nome','email','codigo','username','senha','senha_ant','acoes','status','dt_renovacao','dt_update','limite','qtdAssinantes'])
            ->make(true);

        return $dataTable;
    }

    public function ajaxDestroy(Request $request, Contas $contas)
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
        $op = Servicos::find($idReg);

        if($op->delete()){
            $resp['ok'] = true;
            $resp['permissao'] = true;
        }

        return response()->json($resp);

    }

    public function estatisticas(Request $request)
    {

        if(!$request->ajax()){
            return false;
        }

        $servicos = Servicos::count();
        $servAtivos = Servicos::whereIn('se_status', ['ativa','davez'])->where('se_tipo', 'Premium')->count();

//        $renovacoes = Controle::whereBetween('cs_data_termino', [
//            Carbon::today(),
//            Carbon::today()->addDays(7)
//        ])->where('cs_status', 'ativo')->count();

        $renovacoes = Servicos::whereBetween('se_data_renovacao', [
            Carbon::today(),
            Carbon::today()->addDays(7)
        ])->whereIn('se_status', ['ativa','davez'])->count();


        $totais = [
            'servicos' => $servicos,
            'servicos_ativos' => $servAtivos,
            'renovacoes' => $renovacoes
        ];

        return response()->json($totais);

    }

}
