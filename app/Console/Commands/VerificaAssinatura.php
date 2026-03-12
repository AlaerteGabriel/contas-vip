<?php

namespace App\Console\Commands;

use App\Models\Servicos;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        //
        $servicos = Servicos::all();
        foreach ($servicos AS $servico) {

            $hoje = Carbon::today();
            $renovacao = Carbon::parse($servico->se_data_renovacao)->startOfDay();
            $diasRestantes = (int)$hoje->diffInDays($renovacao, false);

            if($diasRestantes === 1){
                //Enviar para fila.

            }
        }
    }
}
