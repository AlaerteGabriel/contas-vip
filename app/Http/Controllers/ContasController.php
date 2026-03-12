<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdRequest;
use App\Models\Contas;
use App\Models\TemplatesEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ContasController extends Controller
{

    const PATH_VIEW = 'pages/contas/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = Contas::all();
        return view(self::PATH_VIEW.'index', ['contas' => $all]);
    }

    public function create()
    {
        $modelo = TemplatesEmail::all();
        return view(self::PATH_VIEW.'create', compact('modelo'));
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'id' => 'nullable|int',
            'co_nome' => 'required|string',
            'co_codigo' => [
                'required',
                'string',
                Rule::unique('cv_contas', 'co_codigo')->ignore($request->id, 'co_id'),
            ],
            'co_limite' => 'integer|nullable',
            'co_url' => 'required|string',
            'co_url_anuncio' => 'string|nullable',
            'co_envio_manual' => 'integer|nullable',
            'co_template_email_id' => 'integer|nullable',
        ]);

        try {

            DB::beginTransaction();

            $id = $data['id'];
            unset($data['id']);
            Contas::updateOrCreate(['co_id' => $id], $data);

            DB::commit();

            return back()->with('success', $this->msgSuccess);

        }catch (\Exception $e){
            DB::rollBack();
            Log::error('Falha ao salvar dados: '.$e->getMessage());
            return back()->with('error', $this->msgError.' | '.$e->getMessage())->withInput();
        }

    }

    public function ajaxEdit(Request $request, IdRequest $idRequest)
    {
        if(!$request->ajax()){
            return false;
        }

        $id = Str::fromBase64($idRequest->input('idReg'));
        $card = Contas::where('co_id', $id)->first();
        $dadosParaJson = $card->toArray();
        return response()->json($dadosParaJson);

    }

    public function getDatatables(Request $request)
    {
        if(!$request->ajax()){
            return false;
        }

        $data = Contas::query();

        // Filtro por pesquisa
        if ($request->filled('busca')){
            $data->where(function ($query) use ($request) {
                $query->where('co_nome', 'like', "%{$request->busca}%");
                $query->orWhere('co_codigo', 'like', "%{$request->busca}%");
            });
        }

        $data->orderBy('co_id', 'desc');

        $dataTable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nome', function($row) {
                return $row->co_nome ?? '--';
            })
            ->addColumn('codigo', function($row) {
                return $row->co_codigo ?? '--';
            })
            ->addColumn('envio_manual', function($row) {
                $rest = $row->co_envio_manual == 1 ? '<i class="fas fa-check-circle text-success"></i>' : 'Não';
                return $rest;
            })
            ->addColumn('acoes', function($row) {

                $btn = '<div class="d-flex align-items-center">';

                $btn.= '<a href="javascript:;" class="btn btn-icon btn-info btn-sm edit" data-id="'.Str::toBase64($row->co_id).'" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Editar"><i class="fas fa-edit" aria-hidden="true"></i></a>';
                $btn.= '<a href="javascript:;" data-id="'.Str::toBase64($row->co_id).'" class="btn btn-icon btn-danger btn-sm ms-1 btdelete" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Excluir permanentemente"><i class="fas fa-trash" aria-hidden="true"></i></a>';
                $btn.= '</div>';

                return $btn;
            })

            //Força a coluna a ser gerada com HTML
            ->rawColumns(['acoes','envio_manual'])
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
