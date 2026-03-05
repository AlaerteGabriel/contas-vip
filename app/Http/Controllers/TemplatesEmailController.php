<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdRequest;
use App\Models\Smtp;
use App\Models\TemplatesEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class TemplatesEmailController extends Controller
{

    const PATH_VIEW = 'pages/settings/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(self::PATH_VIEW.'email-templates');
    }

    public function store(Request $request)
    {

        // 1. Validação dos dados recebidos do formulário
        $validatedData = $request->validate([
            'id'         => 'nullable|int',
            'te_codigo'  => 'required|string|max:100',
            'te_assunto' => 'required|string|max:100',
            'te_modelo'  => 'required'
        ]);

        // 3. Faz o INSERT ou UPDATE no banco de dados
        DB::beginTransaction();

        try {

            TemplatesEmail::updateOrCreate(['te_id' => $validatedData['id']], $validatedData);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao salvar dados template: '.$e->getMessage());
            return back()->with('error', $this->msgError)->withInput();
        }

        return back()->with('success', $this->msgSuccess);
    }

    public function ajaxEdit(Request $request, IdRequest $idRequest)
    {
        if(!$request->ajax()){
            return false;
        }

        $id = Str::fromBase64($idRequest->input('idReg'));
        $card = TemplatesEmail::where('te_id', $id)->first();
        $dadosParaJson = $card->toArray();
        return response()->json($dadosParaJson);

    }

    public function getDatatablesEmailTemplates(Request $request)
    {
        if(!$request->ajax()){
            return false;
        }

        $data = TemplatesEmail::query();

        // Filtro por pesquisa
        if ($request->filled('busca')){
            $data->where(function ($query) use ($request) {
                $query->where('te_assunto', 'like', "%{$request->busca}%");
                $query->orWhere('te_codigo', 'like', "%{$request->busca}%");
            });
        }

        $data->orderBy('te_id', 'desc');

        $dataTable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('codigo', function($row) {
                return $row->te_codigo ?? '--';
            })
            ->addColumn('assunto', function($row) {
                return $row->te_assunto ?? 0;
            })
            ->addColumn('acoes', function($row) {

                $btn = '<div class="d-flex align-items-center">';

                $btn.= '<a href="javascript:;" class="btn btn-icon btn-info btn-sm edit" data-id="'.Str::toBase64($row->te_id).'" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Editar"><i class="fas fa-edit" aria-hidden="true"></i></a>';
                $btn.= '<a href="javascript:;" data-id="'.Str::toBase64($row->te_id).'" class="btn btn-icon btn-danger btn-sm ms-1 btdelete" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Excluir permanentemente"><i class="fas fa-trash" aria-hidden="true"></i></a>';
                $btn.= '</div>';

                return $btn;
            })

            //Força a coluna a ser gerada com HTML
            ->rawColumns(['acoes'])
            ->make(true);

        return $dataTable;
    }

    public function ajaxDestroy(Request $request, Smtp $smtp)
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
        $op = TemplatesEmail::find($idReg);

        if($op->delete()){
            $resp['ok'] = true;
            $resp['permissao'] = true;
        }

        return response()->json($resp);

    }

}
