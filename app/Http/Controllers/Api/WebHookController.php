<?php

namespace App\Http\Controllers\Api;

use App\Jobs\EnviarEmailCompraJob;
use App\Jobs\EnviarEmailRealocacaoJob;
use App\Jobs\EnviarEmailRenovacaoJob;
use App\Models\Clientes;
use App\Models\Contas;
use App\Models\Controle;
use App\Models\Pedidos;
use App\Services\AlocacaoServicoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    public function handle(Request $request, AlocacaoServicoService $alocacaoServico)
    {

        // Exemplo de validação básica
        $data = $request->validate([
            'pedido' => 'required|string',
            'nome' => 'required|string',
            'email' => 'required|string|email',
            'sku' => 'required|string',
        ]);

        DB::beginTransaction();

        try{

            //pegando o código do serviço e o período que vem no formato "XX030,"
            list($servico) = explode(',', $data['sku']);
            //pegando o código do serviço
            $conta = substr($servico, 0, 2);
            //pegando apenas o período
            $periodo = (int)substr($servico, 3);
            //flag de controle que indica se já é cliente ou não.
            $jaComprou = true;

            //Definindo datas
            $dataInicio = date('Y-m-d');
            $dataTermino = Carbon::now()->addDays($periodo);
            $vencimento = $dataTermino->format('d/m/Y');
            $dataTermino = $dataTermino->toDateString();

            //pegando a conta de serviço com menor número de assinantes e ativa.
            $servConta = Contas::where('co_codigo', $conta)->first();
            //se não tiver o sistema retorna falso.
            if(!$servConta){
                return response()->json(['status' => 'serviço não encontrado'], 200);
            }

            $temServico = $alocacaoServico->buscarMelhorServico($servConta->co_id);
            if (!$temServico) {
                Log::critical("Sem vagas! na conta {$servConta->co_id}");
                return null; // Retorna null para o Controller saber que falhou
            }

            //verifico se o cliente existe, senão cadastro ele e obtenho o ID.
            $cliente = Clientes::where('cl_email', $data['email'])->first();
            if($cliente){
                $idCliente = $cliente->cl_id;
            }else{

                $cliente = Clientes::create([
                    'cl_nome' => $data['nome'],
                    'cl_email' => $data['email'],
                    'cl_email_envio' => $data['email'],
                ]);

                $idCliente = $cliente->cl_id;
                $jaComprou = false;
            }


            if(!$jaComprou){

                $pedido = [
                    'pe_servico' => $servConta->co_nome,
                    'pe_cliente_id' => $idCliente,
                    'pe_sku' => $servico,
                    'pe_detalhes' => $data['pedido'],
                    'pe_data_inicio' => $dataInicio,
                    'pe_data_termino' => $dataTermino,
                    'pe_periodo' => $periodo,
                    'pe_tipo_venda' => 'Nova compra',
                    'pe_data_pedido' => $dataInicio
                ];

                $idPedido = Pedidos::create($pedido);

            }else{

                $pedido = Pedidos::where('pe_cliente_id', $idCliente)
                    ->where('pe_sku', $servico)
                    ->whereDate('pe_data_termino', '>=', now())
                    ->orderBy('pe_id', 'desc')
                    ->first();

                $dados = [
                    'pe_servico' => $servConta->co_nome,
                    'pe_cliente_id' => $idCliente,
                    'pe_sku' => $servico,
                    'pe_detalhes' => $data['pedido'],
                    'pe_data_inicio' => $dataInicio,
                    'pe_data_termino' => $dataTermino,
                    'pe_periodo' => $periodo,
                    'pe_data_pedido' => $dataInicio
                ];

                $renovacao = false;

                if($pedido){
                    $dataTermino = Carbon::parse($pedido->pe_data_termino)->addDays($periodo)->toDateString();
                    $renovacao = true;
                    $dados['pe_tipo_venda'] = 'Renovação';
                    $dados['pe_data_termino'] = $dataTermino;
                    $dados['pe_data_inicio'] = $pedido->pe_data_inicio;
                    $dados['pe_servico'] = $pedido->pe_servico;
                }else{
                    $dados['pe_tipo_venda'] = 'Nova compra';
                }

                $idPedido = Pedidos::create($dados);
            }

            if(!$renovacao) {

                $pivot = [
                    'cs_status' => 'ativo',
                    'cs_pedido_id' => $idPedido->pe_id,
                    'cs_template_email_id' => 0,
                    'cs_data_inicio' => $dataInicio,
                    'cs_data_termino' => $dataTermino
                ];

                $servAlocado = $alocacaoServico->alocarNovoCliente($cliente, $servConta->co_id, $pivot);
                $servAlocado = $servAlocado->se_id;

            }else{
                $controle = Controle::where('cs_pedido_id', $pedido->pe_id)->first();
                $servAlocado = $controle->cs_servico_id;
                $controle->cs_data_termino = $dataTermino;
                $controle->cs_pedido_id = $idPedido->pe_id;
                $controle->save();
            }

            DB::commit();

            //Dispara o job para envio de email de nova compra
            if(!$renovacao) {
                EnviarEmailCompraJob::dispatch($servAlocado, $idCliente, $vencimento)->afterCommit();
            }else{
                EnviarEmailRenovacaoJob::dispatch($servAlocado, $idCliente, $vencimento)->afterCommit();
            }

            return response()->json(['status' => 'sucesso'], 200);

        }catch (\Exception $e){
            // Salvar log
            Log::error('pedido nao encontrado', ['error' => $e->getMessage()]);
            // Operação não é concluída com êxito
            DB::rollBack();

            return response()->json(['status' => 'pedido nao encontrado'.' | '.$e->getMessage()], 404);
        }

    }

    public function alterarConta(Request $request, AlocacaoServicoService $alocacaoServico)
    {
        return view('suporte/index');
    }

    public function alterarContaStore(Request $request, AlocacaoServicoService $alocacaoServico)
    {
        // Exemplo de validação básica
        $data = $request->validate([
            'email' => 'required|email',
            'conta' => 'required|string|max:2',
        ]);

        DB::beginTransaction();

        try{

            //pegando a conta de serviço com menor número de assinantes e ativa.
            $servConta = Contas::with('servicos')->where('co_codigo', $data['conta'])->first();
            //se não tiver o sistema retorna falso.
            if(!$servConta){
                return back()->with('error', 'Conta do Serviço não existe')->withInput();
            }elseif($servConta->servicos->isEmpty()){
                return back()->with('error', 'Serviço não existe')->withInput();
            }

            $cliente = Clientes::where('cl_email', $data['email'])->first();
            if(!$cliente){
                return back()->with('error', 'Cliente não encontrado')->withInput();
            }

            $controle = Controle::with(['servico' => function($query) use ($servConta) {
                $query->where('se_contas_id', $servConta->co_id);
            }])->where('cs_cliente_id', $cliente->cl_id)->first();

            $novoServ = $alocacaoServico->buscarMelhorServico($servConta->co_id);
            $controle->cs_servico_id = $novoServ->se_id;
            $controle->save();

            DB::commit();

            // Dispara o e-mail pro cliente com a nova senha e o serviço que ele caiu
            EnviarEmailRealocacaoJob::dispatch($cliente, $novoServ)->afterCommit();

            return back()->with('success', $this->msgSuccess);

        }catch (\Exception $e){
            // Salvar log
            Log::error('Falha ao mudar conta do cliente via link suporte', ['error' => $e->getMessage()]);
            // Operação não é concluída com êxito
            DB::rollBack();

            return back()->with('error', $this->msgError.$e->getMessage())->withInput();
        }
    }
}
