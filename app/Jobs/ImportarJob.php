<?php

namespace App\Jobs;

use App\Imports\VendedoresImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Facades\Excel;

class ImportarJob implements ShouldQueue
{
    use Queueable;

    public $tries = 5; // aumenta para 5 tentativas
    public $timeout = 300; // aumenta tempo de execução, em segundos

    /**
     * Create a new job instance.
     */
    public function __construct(protected $dados)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try{
            //
            $file = storage_path('app/public/'.$this->dados['path']);
            if(!file_exists($file)){
                Log::info('Arquivo não existe - '.$file);
                return;
            }

            Excel::queueImport(new VendedoresImport($this->dados['idCdl'], $this->dados['mailme']), $file)->onQueue('imports')
                ->allOnQueue('imports');

        }catch(\Exception $e) {
            // Operação não é concluída com êxito
            Log::info('Falha no ImportarJob.php', ['error' => $e->getMessage().' | Caminh:'.$this->dados['path']]);
        }
    }

    public function failed()
    {
        // Log the failure message
        // Notify the admin of the failure
        Log::info('Falha ao importar arquivo - failed');
    }
}
