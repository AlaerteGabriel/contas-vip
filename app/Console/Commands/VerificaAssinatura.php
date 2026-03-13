<?php

namespace App\Console\Commands;

use App\Models\Servicos;
use App\Models\Smtp;
use App\Services\SmtpMailConfig;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerificaAssinatura extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verifica-assinatura';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia aviso ao administrador para as assinaturas que estão vencendo.';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        if (!SmtpMailConfig::apply()) {
            return false;
        }

        //
        $servicos = Servicos::whereIn('se_status', ['ativa','davez'])->whereNot('se_tipo', 'Free')->get();
        if(!$servicos){
            return false;
        }

        foreach ($servicos AS $servico) {

            if(!$servico->se_data_renovacao){
                continue;
            }

            $hoje = Carbon::today();
            $renovacao = Carbon::parse($servico->se_data_renovacao)->startOfDay();
            $diasRestantes = (int)$hoje->diffInDays($renovacao, false);

            if ($renovacao->greaterThan($hoje)) {

                if($diasRestantes === 1){
                    //Enviar para fila.
                }

            }else{
                $servico->se_tipo = 'Free';
                $servico->save();
            }


        }
    }
}
