<?php

namespace App\Jobs;

use App\Mail\AvisoContaAtualizadaMail;
use App\Mail\SendEmail;
use App\Models\Clientes;
use App\Models\Servicos;
use App\Models\Smtp;
use App\Models\TemplatesEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnviarEmailAtualizacaoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Se falhar (ex: SMTP caiu), o Laravel tenta de novo até 3 vezes antes de dar erro definitivo
    public $tries = 3;

    public function __construct(protected $idServico, protected $idCliente)
    {

    }

    public function handle(): void
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

        $novoServico = Servicos::with('conta')->find($this->idServico);
        $cliente = Clientes::find($this->idCliente);

        switch ($novoServico->conta->co_cod) {
            case 'EV':
                $temp = 'ASCPEV';
            break;
            case 'FP':
                $temp = 'ASCPFP';
            break;
            case 'PE':
                $temp = 'ASCPPE';
            break;
            case 'RB':
                $temp = 'ASCPRB';
            break;
            case 'XR':
                $temp = 'ASCPXR';
            break;
            default:
                $temp = 'AS';

        }

        $modelo = TemplatesEmail::where('te_codigo', $temp)->first();

        $primeiroNome = trim($cliente->cl_nome);
        $assunto = str_replace('{servico}', $novoServico->se_nome, $modelo->te_assunto);
        $mensagem = str_replace('{servico}', $novoServico->se_nome, $modelo->te_modelo);
        $mensagem = str_replace('{linkserver}', $novoServico->conta->co_url, $mensagem);
        $mensagem = str_replace('{username}', $novoServico->se_username, $mensagem);
        $mensagem = str_replace('{senhaatual}', $novoServico->se_senha_atual, $mensagem);
        $mensagem = str_replace('{nome}', $primeiroNome, $mensagem);

        $emailEnvio = $cliente->cl_email_envio ?? $cliente->cl_email;

        try {
            // 4. Dispara o Mailable
            Mail::to($emailEnvio)->send(new SendEmail($assunto, $mensagem));
        }catch (\Throwable $e){
            // Se a conexão com o SMTP falhar, cair na malha fina ou der erro de autenticação:
            // A. Loga o erro exato e QUAL foi o SMTP que falhou (fundamental para debugar)
            Log::error("Falha ao enviar e-mail pelo SMTP ID [{$smtp->sm_id}]. Erro: " . $e->getMessage());

            // B. (OPCIONAL E MUITO RECOMENDADO) Desativa temporariamente o SMTP ruim
            // Assim o próximo Job da fila não vai tropeçar no mesmo servidor quebrado
            $smtp->update(['sm_padrao' => null]);

            // C. Devolve o Job para a fila com um atraso de 30 segundos
            // Como o método inRandomOrder() é usado lá em cima, na próxima tentativa ele vai pegar um SMTP diferente
            $this->release(30);
        }

    }
}
