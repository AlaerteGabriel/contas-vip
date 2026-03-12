<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Models\Contas;
use App\Models\Controle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ClientesController extends Controller
{

    const PATH_VIEW = 'pages/clientes/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = Clientes::all();
        return view(self::PATH_VIEW.'index', ['clientes' => $all]);
    }

    public function create()
    {
        return view(self::PATH_VIEW.'create');
    }

    public function edit(int $id, Request $request)
    {
        $cliente = Clientes::find((int)$id);
        if(!$cliente){
            return back()->with('error', 'Cliente não encontrado')->withInput();
        }

        return view(self::PATH_VIEW.'create', compact('cliente'));
    }

    public function store(Request $request)
    {

        $validar = [
            'id'             => 'nullable|int',
            'cl_nome'        => 'required|string|max:100',
            'cl_email' => [
                'required',
                'email',
                Rule::unique('cv_clientes', 'cl_email')->ignore($request->id, 'cl_id'),
            ],
            'cl_email_envio' => 'nullable|email',
            'cl_cel'         => 'nullable|string|max:15',
            'cl_banido'      => 'nullable|int',
            'cl_obs'         => 'nullable|string',
            'cl_username'    => 'nullable|string',
        ];

        $data = $request->validate($validar);

        DB::beginTransaction();

        try {

            $id = $data['id'] ?? null;
            unset($data['id']);

            if(empty($data['cl_banido'])){
                $data['cl_banido'] = 0;
                Controle::where('cs_cliente_id', $id)->update(['cs_status' => 'ativo']);
            }

            Clientes::updateOrCreate(['cl_id' => $id], $data);

            DB::commit();

            return redirect()->route('dashboard.clientes.index')->with('success', $this->msgSuccess);

        }catch (\Exception $e){
            DB::rollBack();
            Log::error('Falha ao salvar dados: '.$e->getMessage());
            return back()->with('error', $this->msgError)->withInput();
        }
    }

    public function getDatatables(Request $request)
    {
        if(!$request->ajax()){
            return false;
        }

        $data = Clientes::query();

        // Filtro por pesquisa
        if ($request->filled('busca')){
            $data->where(function ($query) use ($request) {
                $query->where('cl_nome', 'like', "%{$request->busca}%");
                $query->orWhere('cl_email', 'like', "%{$request->busca}%");
            });
        }

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
            ->addColumn('obs', function($row) {
                return $row->cl_obs;
            })
            ->addColumn('acoes', function($row) {

                $btn = '<div class="d-flex align-items-center">';

                $btn.= '<a href="'.route('dashboard.clientes.edit', $row->cl_id).'" class="btn btn-icon btn-info btn-sm edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Editar"><i class="fas fa-edit" aria-hidden="true"></i></a>';
                $btn.= '<a href="javascript:;" data-id="'.Str::toBase64($row->cl_id).'" class="btn btn-icon btn-danger btn-sm ms-1 btdelete" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Excluir permanentemente"><i class="fas fa-trash" aria-hidden="true"></i></a>';
                $btn.= '</div>';

                return $btn;
            })

            //Força a coluna a ser gerada com HTML
            ->rawColumns(['nome','email','acoes','banido'])
            ->make(true);

        return $dataTable;
    }

    public function ajaxDestroy(Request $request, Contas $contas)
    {

        if(!$request->ajax()){
            return false;
        }

        $validated = $request->validate([
            'idReg' => 'required|string',
        ]);

        $resp['ok'] = false;
        $resp['permissao'] = false;
        $resp['st'] = false;

        $idReg = Str::fromBase64($validated['idReg']);
        $op = Contas::find($idReg);

        if($op->delete()){
            $resp['ok'] = true;
            $resp['permissao'] = true;
        }

        return response()->json($resp);

    }

}
