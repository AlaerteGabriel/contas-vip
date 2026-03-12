<?php

namespace App\Console\Commands;

use App\Jobs\RealocarClientesUpdateJob;
use App\Models\Controle;
use App\Models\Servicos;
use Illuminate\Console\Command;

class LimparServicosDesligados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:limpar-servicos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpar servicos desligados e com tipo free';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        //
        $servicosParaLimpar = Servicos::whereIn('se_status', ['desligada'])->orWhere('se_tipo', 'Free')
            ->whereHas('clientes') // Só traz o serviço se ele não estiver vazio, pra poupar Job
            ->get();

        foreach ($servicosParaLimpar AS $servico) {
            // Despacha UM único Job por serviço! O Job faz o resto.
            RealocarClientesUpdateJob::dispatch($servico);
        }

    }
}
