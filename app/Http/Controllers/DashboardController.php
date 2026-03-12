<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Contas;
use App\Models\Pedidos;
use App\Models\Servicos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{

    const PATH_VIEW = 'dashboard/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return view(self::PATH_VIEW.'index', []);
    }

    public function estatisticas(Request $request)
    {

        if(!$request->ajax()){
            return false;
        }

        $servicos = Servicos::count();
        $clientes = Clientes::count();
        $contas = Contas::count();
        $pedidos = Pedidos::count();

        $totais = [
            'servicos' => $servicos,
            'clientes' => $clientes,
            'pedidos' => $pedidos,
            'contas' => $contas
        ];

        return response()->json($totais);

    }

    public function getDatatables(Request $request)
    {
        if(!$request->ajax()){
            return false;
        }

        $data = Clientes::where('created_at', '>=', Carbon::now()->subDay());
        $data->orderBy('cl_id', 'desc');

        $dataTable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nome', function($row) {

                preg_match_all('/\b\w/u', $row->cl_nome, $matches);
                $iniciais = Str::limit(implode('', $matches[0]), 2, false);

                $html = '<div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                '.strtoupper($iniciais).'
                                </div>
                                <h6 class="mb-0 fw-semibold">'.$row->cl_nome.'</h6>
                            </div>';
                return $html;
            })
            ->addColumn('email', function($row) {
                return '<i class="fa-regular fa-envelope text-muted me-1"></i>'.$row->cl_email;
            })
            ->addColumn('cel', function($row) {
                return $row->cl_cel;
            })
            ->addColumn('banido', function($row) {
                $rest = $row->cl_banido == 1 ? '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3"><i class="fa-solid fa-ban me-1"></i> Sim</span>' : '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Não</span>';
                return $rest;
            })

            //Força a coluna a ser gerada com HTML
            ->rawColumns(['nome','email','banido'])
            ->make(true);

        return $dataTable;
    }

}
