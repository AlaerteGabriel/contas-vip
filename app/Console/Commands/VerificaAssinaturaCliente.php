<?php

namespace App\Console\Commands;

use App\Mail\SendEmail;
use App\Models\Controle;
use App\Models\Smtp;
use App\Models\TemplatesEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerificaAssinaturaCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verifica-assinatura-cliente';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia aviso ao cliente para as assinaturas que estão vencendo.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // 1. Seleciona o SMTP. Pode usar uma lógica para pegar o mais vazio,
        // ou um específico da conta. Aqui pego o primeiro ativo como exemplo.
        $smtp = Smtp::where('sm_padrao', 1)->inRandomOrder()->first();
        if (!$smtp) {
            Log::error('Nenhum servidor SMTP configurado ou ativo na tabela cv_smtp.');
            return;
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

        //
        $controles = Controle::with(['cliente', 'servico','templateEmail'])
            ->whereHas('cliente', fn($q) => $q->where('cl_banido', 0))
            ->whereHas('servico', fn($q) => $q->whereNot('se_status', 'fechada')->whereNot('se_tipo','Free'))
            ->where('cs_status', 'ativo')
            ->get();

        $templates = TemplatesEmail::whereIn('te_codigo', ['X', 'F'])->get();
        $expirando = $templates->firstWhere('te_codigo', 'X');
        $expirado = $templates->firstWhere('te_codigo', 'F');


        $hora = Carbon::now()->hour;

        if ($hora >= 6 && $hora < 12) {
            $saudacao = 'Bom dia';
        } elseif ($hora >= 12 && $hora < 18) {
            $saudacao = 'Boa tarde';
        } else {
            $saudacao = 'Boa noite';
        }

        foreach ($controles AS $controle) {

            $hoje = Carbon::today();
            $renovacao = Carbon::parse($controle->cs_data_termino)->startOfDay();
            $diasRestantes = (int)$hoje->diffInDays($renovacao, false);
            $primeiroNome = trim($controle->cliente->cl_nome);
            $emailEnvio = ($controle->cliente->cl_email_envio) ? trim($controle->cliente->cl_email_envio) : $controle->cliente->cl_email;

            if ($renovacao->greaterThan($hoje)) {

                if($diasRestantes === 2){

                    $assunto = str_replace('{servico}', $primeiroNome, $expirando->te_assunto);
                    $mensagem = str_replace('{servico}', $controle->servico->se_nome, $expirando->te_modelo);
                    $mensagem = str_replace('{linkrenovPT}', '', $mensagem);
                    $mensagem = str_replace('{nome}', $primeiroNome, $mensagem);
                    $mensagem = str_replace('{saudacao}', $saudacao, $mensagem);

                    if($controle->templateEmail) {
                        $assunto2  = $controle->templateEmail->te_assunto;
                        $mensagem2 = str_replace('{nome}', $primeiroNome, $controle->templateEmail->te_modelo);
                        Mail::to($emailEnvio)->queue(new SendEmail($assunto2, $mensagem2));
                    }

                    //enviar o email pra fila de lembrete cod X lembrete
                    Mail::to($emailEnvio)->queue(new SendEmail($assunto, $mensagem));
                }

            }else{

                //enviar o email pra fila de expirado cod F expirado
                $assunto = str_replace('{servico}', $primeiroNome, $expirado->te_assunto);
                $mensagem = str_replace('{servico}', $controle->servico->se_nome, $expirado->te_modelo);
                $mensagem = str_replace('{linkrenovPT}', '', $mensagem);
                $mensagem = str_replace('{nome}', $primeiroNome, $mensagem);
                $mensagem = str_replace('{saudacao}', $saudacao, $mensagem);
                //envia o email de aviso para fila.
                Mail::to($emailEnvio)->queue(new SendEmail($assunto, $mensagem));

                $controle->delete();
            }

        }

    }
}
