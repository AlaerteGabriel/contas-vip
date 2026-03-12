<?php
namespace App\Jobs;

use App\Models\Servicos;
use App\Models\Controle;
use App\Services\AlocacaoServicoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RealocarClientesUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $servico;
    public $timeout = 300;

    public function __construct(Servicos $servico)
    {
        $this->servico = $servico;
    }

    public function handle() : void
    {

        $alocacaoService = new AlocacaoServicoService();

        $assinaturas = Controle::with(['cliente'])
            ->where('cs_servico_id', $this->servico->se_id)
            ->whereHas('cliente', function ($query) {
                $query->where('cl_banido', 0);
            })
            ->get();

        if ($assinaturas->isEmpty()) {
            return;
        }

        $clientesParaRealocar = [];

        // ==========================================
        // ETAPA 1: ESVAZIAMENTO TOTAL (O Expurgo)
        // ==========================================
        foreach ($assinaturas AS $assinatura) {
            // Salva os dados na memória antes de apagar
            $clientesParaRealocar[] = [
                'cliente' => $assinatura->cliente,
                'dadosPivot' => $assinatura->toArray()
            ];

            // Deleta o controle. Isso aciona o seu Model Observer/Booted
            // e vai subtraindo a lotação do serviço um por um até chegar a ZERO.
            $assinatura->delete();
        }

        // Garante que o Laravel atualize a memória do serviço para ver que a lotação agora é 0
        $this->servico->refresh();

        // ==========================================
        // ETAPA 2: REDISTRIBUIÇÃO JUSTA
        // ==========================================
        $delaySegundos = 1;
        foreach ($clientesParaRealocar AS $item) {
            $cliente = $item['cliente'];
            $dadosPivot = $item['dadosPivot'];

            unset($dadosPivot['cs_id'], $dadosPivot['cs_servico_id'], $dadosPivot['cs_cliente_id']);

            try {
                // A MUDANÇA: Não passamos mais o ID para ignorar.
                // Como o serviço atual agora tem 0 clientes, ele VAI ser o escolhido pelo Service!
                $novoServico = $alocacaoService->buscarMelhorServico($this->servico->se_contas_id);

                if (!$novoServico) {
                    Log::critical("Falta de vagas! Cliente ID {$cliente->cl_id} não pôde ser realocado.");
                    continue;
                }

                // Realiza a nova alocação
                DB::transaction(function () use ($cliente, $novoServico, $dadosPivot) {

                    $dadosNovos = array_merge($dadosPivot, [
                        'cs_cliente_id' => $cliente->cl_id,
                        'cs_servico_id' => $novoServico->se_id,
                    ]);

                    Controle::create($dadosNovos);
                    $novoServico->increment('se_qtd_assinantes');
                });

                // Dispara o e-mail pro cliente com a nova senha e o serviço que ele caiu
                EnviarEmailRealocacaoJob::dispatch($cliente, $novoServico)->delay(now()->addSeconds($delaySegundos));
                $delaySegundos+=2;

            } catch (\Throwable $e) {
                // Se der qualquer B.O. (lock de tabela, erro de integridade, dado faltando)
                // O sistema captura, grita no log, e o `continue` manda o loop ir para o próximo cliente
                Log::error("Erro fatal ao realocar cliente ID {$cliente->cl_id}. Erro: " . $e->getMessage());
                continue;
            }

        }

        Log::info("Serviço ID {$this->servico->se_id} resetado. {$assinaturas->count()} clientes foram redistribuídos com sucesso.");
    }
}
