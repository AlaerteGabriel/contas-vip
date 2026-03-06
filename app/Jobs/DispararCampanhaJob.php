<?php

namespace App\Jobs;

use App\Models\EmailMarketing;
use App\Models\EmailMarketingLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable; // No Laravel 11/12 usa-se este trait
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Throwable;

class DispararCampanhaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Tempo máximo de execução do Job (em segundos) - útil para listas grandes
    public $timeout = 1200;

    /**
     * Create a new job instance.
     */
    public function __construct(private $campanhaId)
    {
        // $this->campanhaId contém o ID da campanha
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Busca a campanha e o template associado
        $campanha = EmailMarketing::with('template')->find($this->campanhaId);

        // Trava de segurança
        if (!$campanha || $campanha->em_status === 'finalizado') {
            return;
        }

        // 2. Altera o status geral para processando
        $campanha->update(['em_status' => 'executando']);

        // 3. Puxa os logs na fila em lotes de 100 para não estourar a memória
        $logsParaEnviar = EmailMarketingLog::where('eml_email_marketing_id', $campanha->em_id)
            ->where('eml_status', 'fila')
            ->limit($campanha->em_limite_envios)
            ->get();

        if ($logsParaEnviar->isEmpty()) {
            // Se não tem mais ninguém na fila, a campanha acabou de verdade
            $campanha->update(['em_status' => 'finalizada']);
            return;
        }

        // Variável para controlar o tempo de espera crescente
        $delaySegundos = 0;

        // 2. Despacha um Job INDIVIDUAL para cada e-mail
        foreach ($logsParaEnviar AS $log) {
            // Muda o status para não ser pego de novo caso o Job Gerente rode duas vezes
            $log->update(['eml_status' => 'processando']);

            // Joga para a fila do Laravel
            EnviarEmailCampanhaJob::dispatch($log->eml_id, $campanha->em_id)->delay(now()->addSeconds($delaySegundos));
            $delaySegundos += 2;
        }

        // 3. Verifica se ainda sobrou gente na fila para o dia seguinte
        $restantes = EmailMarketingLog::where('eml_email_marketing_id', $campanha->em_id)
            ->where('eml_status', 'fila')
            ->count();

        if ($restantes > 0) {
            // Pausada (ou Aguardando) significa que precisará de um novo gatilho amanhã
            $campanha->update(['em_status' => 'pausada']);
        } else {
            // Todos foram enfileirados
            $campanha->update(['em_status' => 'finalizada']);
        }


    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        // Se o Job inteiro quebrar (ex: servidor de e-mail caiu, timeout), cai aqui
        Log::error("Falha catastrófica no Job DispararCampanhaJob (Campanha ID: {$this->campanhaId}): " . $exception->getMessage());

        // Atualiza a campanha para status de erro para o admin ver na tela
        EmailMarketing::where('em_id', $this->campanhaId)->update(['em_status' => 'erro']);
    }
}
