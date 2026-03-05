<?php

namespace App\Imports;

use App\Models\Produtos;
use App\Traits\ProductImageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class DadosImport implements ToCollection, WithHeadingRow
{

    use ProductImageTrait;
    public function collection(Collection $rows)
    {

        $produtos = [];
        $icone = null;

        foreach ($rows AS $key => $row) {

            $prod = Produtos::create([
                //
                'pr_id_operador' => Auth::guard('admin')->id(),
                'pr_id_categoria' => 1,
                'pr_id_marca' => 1,
                'pr_icone' => $row['pr_icone'] ?? null,
                'pr_nome' => $row['pr_nome'],
                'pr_slug' => Str::slug($row['pr_nome']),
                'pr_descricao' => $row['pr_descricao'] ?? null,
                'pr_descricao_curta' => $row['pr_descricao'] ?? null,
                'pr_qtdEstoque' => $row['pr_qtdestoque'] ?? 1,
                'pr_valor' => $row['pr_valor'],
                'pr_sku' => $row['pr_sku'] ?? null,
                'pr_codbarras' => $row['pr_codbarras'] ?? null
            ]);

            if(!$row['pr_icone']){
                $icone = $this->getImageProduct($row['pr_nome'], $prod->pr_id);
                $prod->pr_icone = $icone;
                $prod->save();
            }

//            $produtos[] = [
//                //
//                'pr_id_operador' => Auth::guard('admin')->id(),
//                'pr_id_categoria' => 1,
//                'pr_id_marca' => 1,
//                'pr_icone' => $row['pr_icone'],
//                'pr_nome' => $row['pr_nome'],
//                'pr_slug' => Str::slug($row['pr_nome']),
//                'pr_descricao' => $row['pr_descricao'] ?? null,
//                'pr_descricao_curta' => $row['pr_descricao'] ?? null,
//                'pr_qtdEstoque' => $row['pr_qtdestoque'] ?? 1,
//                'pr_valor' => $row['pr_valor'],
//                'pr_sku' => $row['pr_sku'] ?? null,
//                'pr_codbarras' => $row['pr_codbarras'] ?? null
//            ];

        }

        return true;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',', // Altere para ';' se necessário
            'input_encoding' => 'UTF-8',
        ];
    }
}
