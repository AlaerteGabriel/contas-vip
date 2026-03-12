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
        // Ignoramos o Laravel e pegamos o iterador de células direto da fonte
        $celulas = $row->getDelegate()->getCellIterator();
        $celulas->setIterateOnlyExistingCells(false);

        dd($row);
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
