<?php

namespace App\Http\Controllers;

use App\Jobs\DispararCampanhaJob;
use App\Models\Clientes;
use App\Models\EmailMarketing;
use App\Models\EmailMarketingLog;
use App\Models\Pedidos;
use App\Models\Servicos;
use App\Models\TemplatesEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmailMarketingController extends Controller
{

    const PATH_VIEW = 'pages/settings/';

    public function index()
    {
        // Carrega os dados para popular os selects do seu layout Blade
        $servicos = Servicos::orderBy('se_nome')->get();
        $templates = TemplatesEmail::orderBy('te_assunto')->get();

        // Traz as campanhas com a relação do template e já conta quantos logs têm status 'enviado'
        $campanhas = EmailMarketing::with('template')
            ->withCount(['logs AS qtd_enviados' => function ($query) {
                $query->where('eml_status', 'enviado');
            }])
            ->orderByDesc('created_at')
            ->paginate($this->registrosPorPagina); // Paginação padrão do Laravel

        return view(self::PATH_VIEW.'email-marketing', compact('servicos', 'templates', 'campanhas'));
    }

    public function criar(Request $request)
    {

        $validated = $request->validate([
            'status_filtro' => 'nullable|in:ativo,expirado',
            'servico_id' => 'nullable|exists:cv_servicos,se_id',
            'tipo_venda' => 'nullable|string',
            'data_inicio' => 'nullable|date',
            'data_termino' => 'nullable|date|after_or_equal:data_inicio',
        ]);

        DB::beginTransaction();

        try {

            // 3. Monta a Query para filtrar os contatos baseados nas regras
            $query = Pedidos::query()
                ->join('cv_clientes', 'cv_pedidos.pe_cliente_id', '=', 'cv_clientes.cl_id')
                ->select('cv_clientes.cl_id', 'cv_clientes.cl_email', 'cv_clientes.cl_nome');
                // Ignora quem tem o "X" de não receber e-mail (ajuste para o seu campo real)
                //->where('cl_banido', false);

            // Aplica os filtros dinamicamente se foram preenchidos
            if (!empty($validated['status_filtro'])) {

                if ($validated['status_filtro'] === 'ativo') {
                    // Traz apenas quem tem o vencimento de hoje para o futuro
                    $query->where('pe_data_termino', '>=', now()->startOfDay());

                } elseif ($validated['status_filtro'] === 'expirado') {
                    // Traz apenas quem o vencimento já passou (ontem para trás)
                    $query->where('pe_data_inicio', '<', now()->startOfDay());
                }

            }

            // Aplica os filtros dinamicamente se foram preenchidos
            if (!empty($validated['servico_id'])) {
                $query->where('pe_servico', $validated['servico_id']);
            }

            // 4. Remove duplicatas (mesmo cliente com 2 vendas caindo no filtro)
            $query->groupBy('cl_id', 'cl_email', 'cl_nome');

            // 5. Aplica o limite de envios setado pelo usuário
            $contatosFiltrados = $query->get();

            DB::commit();

            if($contatosFiltrados->isEmpty()){
                return back()->with('error', 'Nenhum cliente encontrado para envio do email marketing!')->withInput();
            }

            $templates = TemplatesEmail::orderBy('te_assunto')->get();
            return view(self::PATH_VIEW.'email-marketing-create', compact('templates', 'contatosFiltrados', 'validated'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao filtrar contatos: '.$e->getMessage());
            return back()->with('error', $this->msgError.' | '.$e->getMessage())->withInput();
        }

    }

    public function store(Request $request)
    {

        // 1. Validação dos dados que virão do seu Blade
        $validated = $request->validate([
            'usuarios_ids' => 'required|string',
            'titulo' => 'required|string|max:255',
            'template_id' => 'required|exists:cv_templates_email,te_id',
            'quantidade_envios' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {

            // 1. Salva o cabeçalho da Campanha
            $campanha = EmailMarketing::create([
                'em_titulo' => $validated['titulo'],
                'em_template_id' => $validated['template_id'],
                'em_limite_envios' => $validated['quantidade_envios'],
                'em_status' => 'pendente',
                'em_filtros_aplicados' => $request->input('filtros', [])
            ]);

            $campanhaId = $campanha->em_id;
            $arrayIds = explode(',', $request->usuarios_ids);
            $logsParaInserir = count($arrayIds);

            $clientes = Clientes::whereIn('cl_id', $arrayIds)
                ->select('cl_id', 'cl_email')
                ->chunk(500, function ($clientes) use ($campanhaId) {

                    $logsBatch = [];

                    // 3. Monta os arrays em memória (isso é extremamente rápido no PHP)
                    foreach ($clientes AS $user) {
                        $logsBatch[] = [
                            'eml_email_marketing_id' => $campanhaId,
                            'eml_user_id'            => $user->cl_id,
                            'eml_email_destino'      => $user->cl_email_envio ?? $user->cl_email,
                            'eml_status'             => 'fila'
                        ];
                    }

                    // 4. Executa 1 ÚNICA QUERY de INSERT para salvar os 500 registros de uma vez
                    EmailMarketingLog::insert($logsBatch);
                });

            DB::commit();

            // Aqui você chamaria o seu Job para processar o envio em background
            DispararCampanhaJob::dispatch($campanha->em_id)->afterCommit();
            return redirect()->route('dashboard.config.email-marketing')->with('success', 'Campanha criada e contatos enfileirados com sucesso! Total: ' . $logsParaInserir);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar campanha: '.$e->getMessage());
            return back()->with('error', $this->msgError.' | '.$e->getMessage())->withInput();
        }

    }

}
