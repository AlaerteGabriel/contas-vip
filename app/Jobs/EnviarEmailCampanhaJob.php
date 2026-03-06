<?php

namespace App\Jobs;

use App\Models\EmailMarketing;
use App\Models\EmailMarketingLog;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnviarEmailCampanhaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Se falhar (ex: SMTP caiu), o Laravel tenta de novo até 3 vezes antes de dar erro definitivo
    public $tries = 3;

    public function __construct(private $logId, private $campanhaId)
    {
    }

    public function handle(): void
    {
        // Busca as informações necessárias
        $log = EmailMarketingLog::with('cliente')->find($this->logId);
        $campanha = EmailMarketing::with('template')->find($this->campanhaId);

        if (!$log || !$campanha) return;

        try {
            // Substituição da tag <nome> pelo primeiro nome
            $primeiroNome = trim($log->cliente->cl_nome);
            $assunto = str_replace('{nome}', $primeiroNome, $campanha->template->te_assunto);
            $mensagem = str_replace('{nome}', $primeiroNome, $campanha->template->te_modelo);

            // Dispara o e-mail real
            Mail::to($log->eml_email_destino)->send(new SendEmail($assunto, $mensagem));

            // Marca como sucesso absoluto
            $log->update([
                'eml_status' => 'enviado',
                'eml_enviado_em' => now()
            ]);

        } catch (\Exception $e) {
            // Se falhar (e já tiver esgotado as 3 tentativas do Job), salva o erro na linha específica
            $log->update([
                'eml_status' => 'falhou',
                'eml_msg' => $e->getMessage()
            ]);
        }
    }
}
