<?php

namespace App\Http\Controllers;

use App\Models\Controle;
use App\Models\Pedidos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PedidosController extends Controller
{

    const PATH_VIEW = 'pages/pedidos/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(self::PATH_VIEW.'index');
    }

    public function getDatatables(Request $request)
    {
        if(!$request->ajax()){
            return false;
        }

        $data = Pedidos::with('cliente');

        // Filtro por pesquisa
        if ($request->filled('busca')){

            $busca = $request->busca;
            $data->where(function ($query) use ($request) {
                $query->where('pe_servico', 'like', "%{$request->busca}%");
                $query->orWhere('pe_sku', 'like', "%{$request->busca}%");
            });

        }

        $data->orderBy('pe_id', 'desc');

        $dataTable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('datavenda', function($row) {

                $textClass = 'text-dark';
                $textIconClass = 'text-muted';
                $data = $row->pe_data_pedido->format('d/m/Y');

                $html = '<div class="fw-semibold '.$textClass.' mb-1">
                            <i class="fa-regular fa-calendar '.$textIconClass.' me-1"></i> '.$data.'
                        </div>';

                return $html;

            })
            ->addColumn('codigo', function($row) {
                $html = '<span class="badge bg-light text-dark border font-monospace">'.$row->pe_sku.'</span>';
                return $html;
            })
            ->addColumn('detalhe', function($row) {
                $html = '<code>'.$row->pe_detalhes.'</code>';
                return $html;
            })
            ->addColumn('servico', function($row) {
                return '<h6 class="mb-0 fw-semibold">'.$row->pe_servico.'</h6>';
            })
            ->addColumn('tipo', function($row) {
                $html = $row->pe_tipo_venda;
                return $html;
            })
            ->addColumn('inicio', function($row) {

                $textClass = 'text-dark';
                $textIconClass = 'text-muted';
                $data = $row->pe_data_inicio->format('d/m/Y');

                $html = '<div class="fw-semibold '.$textClass.' mb-1">
                            <i class="fa-regular fa-calendar '.$textIconClass.' me-1"></i> '.$data.'
                        </div>';

                return $html;
            })
            ->addColumn('periodo', function($row) {
                return '<code>'.$row->pe_periodo.' dia(s)</code>';
            })
            ->addColumn('termino', function($row) {

                $textClass = 'text-dark';
                $textIconClass = 'text-muted';
                $data = $row->pe_data_termino->format('d/m/Y');

                $html = '<div class="fw-semibold '.$textClass.' mb-1">
                            <i class="fa-regular fa-calendar '.$textIconClass.' me-1"></i> '.$data.'
                        </div>';

                return $html;
            })
            ->addColumn('obs', function($row) {
                //
                return '<div class="text-muted small fst-italic">'.$row->pe_obs.'</div>';
            })

            //Força a coluna a ser gerada com HTML
            ->rawColumns(['obs','datavenda','codigo','detalhe','servico','inicio','termino','periodo'])
            ->make(true);

        return $dataTable;
    }

}
