<?php


namespace App\Services;

use App\Models\Smtp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SmtpMailConfig
{
    public static function apply() : bool|Smtp
    {

        // 1. Seleciona o SMTP. Pode usar uma lógica para pegar o mais vazio,
        // ou um específico da conta. Aqui pego o primeiro ativo como exemplo.
        $smtp = Smtp::where('sm_padrao', 1)->inRandomOrder()->first();
        if (!$smtp) {
            Log::error('Nenhum servidor SMTP configurado ou ativo na tabela cv_smtp.');
            return false;
        }

        // 2. Injeta as configurações dinâmicas no Laravel
        config([
            'mail.mailers.smtp.host' => $smtp->sm_host,
            'mail.mailers.smtp.port' => $smtp->sm_porta,
            'mail.mailers.smtp.username' => $smtp->sm_login,
            'mail.mailers.smtp.password' => $smtp->sm_senha,
            'mail.mailers.smtp.encryption' => $smtp->sm_protocolo,
            'mail.from.address' => $smtp->sm_email_remetente,
            'mail.from.name' => $smtp->sm_nome,
        ]);

        Mail::purge('smtp');

        return $smtp;
    }
}
