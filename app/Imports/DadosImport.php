<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Row;

class DadosImport implements OnEachRow, WithHeadingRow, WithChunkReading, SkipsEmptyRows
{
    public function onRow(Row $row)
    {

        $linhaLimpa = [];

        // Ignoramos o Laravel e pegamos o iterador de células direto da fonte
        $celulas = $row->getDelegate()->getCellIterator();
        $celulas->setIterateOnlyExistingCells(false);

        foreach ($celulas as $celula) {
            if ($celula->isFormula()) {
                // Arranca o valor do cache visual salvo pelo Excel
                $valor = $celula->getOldCalculatedValue();
            } else {
                // Pega o valor normal
                $valor = $celula->getValue();
            }

            // Adiciona no nosso array limpo
            $linhaLimpa[] = $valor;
        }

        // Printa apenas a primeira linha processada e para o código
        print_r($linhaLimpa);
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
