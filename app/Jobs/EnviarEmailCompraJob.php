<?php

namespace App\Jobs;

use App\Mail\AvisoContaAtualizadaMail;
use App\Mail\SendEmail;
use App\Models\Clientes;
use App\Models\Servicos;
use App\Models\Smtp;
use App\Models\TemplatesEmail;
use App\Services\SmtpMailConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnviarEmailCompraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Se falhar (ex: SMTP caiu), o Laravel tenta de novo até 3 vezes antes de dar erro definitivo
    public $tries = 3;

    public function __construct(private $idServico, private $idCliente, private $vencimento)
    {
    }

    public function handle(): void
    {

        $smtp = SmtpMailConfig::apply();

        $novoServico = Servicos::with('conta')->find($this->idServico);
        $cliente = Clientes::find($this->idCliente);

        switch ($novoServico->conta->co_codigo) {
            case 'EV':
                $temp = 'PGCPEV';
            break;
            case 'FP':
                $temp = 'PGCPFP';
            break;
            case 'PE':
                $temp = 'PGCPPE';
            break;
            case 'RB':
                $temp = 'PGCPRB';
            break;
            case 'XR':
                $temp = 'PGCPXR';
            break;
            case 'SB':
                $temp = 'PGCPSB';
            break;
            default:
                $temp = 'PGCP';

        }

        $modelo = TemplatesEmail::where('te_codigo', $temp)->first();
        if(!$modelo){
            Log::error('template não localizado: '.$temp);
            return;
        }

        $primeiroNome = trim($cliente->cl_nome);
        $assunto = str_replace('{servico}', $novoServico->se_nome, $modelo->te_assunto);
        $mensagem = str_replace('{servico}', $novoServico->se_nome, $modelo->te_modelo);
        $mensagem = str_replace('{linkserver}', $novoServico->conta->co_url, $mensagem);
        $mensagem = str_replace('{username}', $novoServico->se_username, $mensagem);
        $mensagem = str_replace('{senhaatual}', $novoServico->se_senha_atual, $mensagem);
        $mensagem = str_replace('{nome}', $primeiroNome, $mensagem);
        $mensagem = str_replace('{vencimento}', $this->vencimento, $mensagem);

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
