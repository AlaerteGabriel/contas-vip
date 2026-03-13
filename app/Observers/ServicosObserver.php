<?php

namespace App\Observers;

use App\Jobs\RealocarClientesJob;
use App\Jobs\RealocarClientesUpdateJob;
use App\Models\Servicos;
use Illuminate\Support\Facades\Log;

class ServicosObserver
{
    /**
     * Handle the Transacoes "created" event.
     */
    public function updated(Servicos $servico) : void
    {
        //Log::error('disparou');
        // Se o status mudou E o novo status é 'DESLIGADA'
        if ($servico->isDirty('se_status') && $servico->se_status === 'desligada') {
            // Dispara o Job passando o serviço que acabou de ser desligado
            //removi a realocação imediata, pois é pra rodar pelo CRON;
            RealocarClientesUpdateJob::dispatch($servico);
        }
    }

    public function updating(Servicos $servico) : void
    {
        // 1. Verifica se a senha foi alterada na requisição atual
        // (Ajuste 'se_senha_atual' para o nome exato da sua coluna de senha)
        if ($servico->isDirty('se_senha_atual')) {
            // 2. Injeta a data atual na coluna de data de troca
            // (Ajuste 'se_senha_atual' para o nome da sua coluna)
            $senhaAntiga = $servico->getOriginal('se_senha_atual');

            $servico->se_data_update = date('Y-m-d');
            $servico->se_senha_anterior = $senhaAntiga;
            $servico->se_qtd_update = ($servico->se_qtd_update ?? 0) + 1;

            if($servico->se_status == 'ativa'){
                // Dispara o Job passando o serviço que acabou de ser desligado
                RealocarClientesUpdateJob::dispatch($servico);
            }

        }
    }

    public function deleting(Servicos $servico) : void
    {
        // Dispara o Job passando o serviço que acabou de ser desligado
        //RealocarClientesUpdateJob::dispatch($servico);
    }

}
