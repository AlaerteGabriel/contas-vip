<?php

namespace App\Jobs;

use App\Models\EmailMarketing;
use App\Models\EmailMarketingLog;
use App\Mail\SendEmail;
use App\Models\Smtp;
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

            $smtp = Smtp::where('sm_padrao', 1)->inRandomOrder()->first();
            if($smtp) {
                // Aplica a configuração dentro do processo da fila
                config([
                    'mail.mailers.smtp.host' => $smtp->sm_host,
                    'mail.mailers.smtp.port' => $smtp->sm_porta,
                    'mail.mailers.smtp.username' => $smtp->sm_login,
                    'mail.mailers.smtp.password' => $smtp->sm_senha,
                    'mail.mailers.smtp.encryption' => $smtp->sm_protocolo,
                    'mail.from.address' => $smtp->sm_email_remetente,
                    'mail.from.name' => $smtp->sm_nome,
                ]);
            }

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

            // C. Devolve o Job para a fila com um atraso de 30 segundos
            // Como o método inRandomOrder() é usado lá em cima, na próxima tentativa ele vai pegar um SMTP diferente
            $this->release(30);
        }
    }
}
