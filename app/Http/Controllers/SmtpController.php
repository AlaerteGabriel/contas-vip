<?php

namespace App\Http\Controllers;

use App\Http\Requests\IdRequest;
use App\Models\Smtp;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SmtpController extends Controller
{

    const PATH_VIEW = 'pages/settings/';


    /**
     * Display a listing of the resource.
     */
    public function smtp()
    {
        return view(self::PATH_VIEW.'mail-server');
    }

    public function smtpStore(Request $request)
    {

        $validatedData = $request->validate([
            'id'                => 'nullable|int',
            'mail_from_name'    => 'required|string|max:100',
            'mail_from_address' => 'required|email|max:50',
            'mail_host'         => 'required|string|max:50',
            'mail_port'         => 'required|int',
            'mail_encryption'   => 'required|in:none,tls,ssl',
            'mail_username'     => 'required|string|max:50',
            'mail_password'     => 'required|string', // Nullable caso a pessoa mande vazio para manter a senha atual
            'padrao'            => 'nullable|int',
        ]);

        DB::beginTransaction();

        try {

            $id = Smtp::updateOrCreate(['sm_id' => $validatedData['id']],[
                'sm_nome' => $validatedData['mail_from_name'],
                'sm_email_remetente' => $validatedData['mail_from_address'],
                'sm_host' => $validatedData['mail_host'],
                'sm_porta' => $validatedData['mail_port'],
                'sm_protocolo' => $validatedData['mail_encryption'],
                'sm_login' => $validatedData['mail_username'],
                'sm_senha' => $validatedData['mail_password'],
                'sm_padrao' => $validatedData['padrao'] ?? false,
            ]);

            if(isset($validatedData['padrao'])) {
                Smtp::where('sm_id', '<>', $id->sm_id)->update(['sm_padrao' => 0]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Falha ao salvar smtp: '.$e->getMessage());
            return back()->with('error', $this->msgError)->withInput();
        }

        return back()->with('success', $this->msgSuccess);
    }

    public function ajaxSmtpEdit(Request $request, IdRequest $idRequest)
    {
        if(!$request->ajax()){
            return false;
        }

        $id = Str::fromBase64($idRequest->input('idReg'));
        $card = Smtp::where('sm_id', $id)->first();
        $dadosParaJson = $card->toArray();
        return response()->json($dadosParaJson);

    }

    public function getDatatablesSmtp(Request $request)
    {
        if(!$request->ajax()){
            return false;
        }

        $data = Smtp::query();

        // Filtro por pesquisa
        if ($request->filled('busca')){
            $data->where(function ($query) use ($request) {
                $query->where('sm_nome', 'like', "%{$request->busca}%");
                $query->orWhere('sm_email_remetente', 'like', "%{$request->busca}%");
            });
        }

        // Supondo que você receba 'data_inicio' e 'data_fim' no request
        if ($request->filled(['criado', 'atualizado'])){
            $dataInicio = Carbon::parse($request->criado)->startOfDay();
            $dataFim    = Carbon::parse($request->atualizado)->endOfDay();

            // Filtra o campo ca_data entre o início do primeiro dia e o final do último dia
            $data->whereBetween('created_at', [$dataInicio, $dataFim]);
        }elseif($request->filled('criado')){
            $data->where(function ($query) use ($request) {
                $query->whereRaw("DATE(created_at) = '$request->criado'");
            });
        }

        $data->orderBy('sm_id', 'desc');

        $dataTable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('login', function($row) {
                return $row->sm_email_remetente ?? '--';
            })
            ->addColumn('host', function($row) {
                return $row->sm_host ?? 0;
            })
            ->addColumn('padrao', function($row) {
                $rest = $row->sm_padrao == 1 ? '<i class="fas fa-check-circle text-success"></i>' : '--';
                return $rest;
            })
            ->addColumn('acoes', function($row) {

                $btn = '<div class="d-flex align-items-center">';

                $btn.= '<a href="javascript:;" class="btn btn-icon btn-info btn-sm edit" data-id="'.Str::toBase64($row->sm_id).'" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Editar"><i class="fas fa-edit" aria-hidden="true"></i></a>';
                $btn.= '<a href="javascript:;" data-id="'.Str::toBase64($row->sm_id).'" class="btn btn-icon btn-danger btn-sm ms-1 btdelete" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="top" data-bs-original-title="Excluir permanentemente"><i class="fas fa-trash" aria-hidden="true"></i></a>';
                $btn.= '</div>';

                return $btn;
            })

            //Força a coluna a ser gerada com HTML
            ->rawColumns(['acoes','padrao'])
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
        $op = Smtp::find($idReg);

        if($op->sm_padrao){
            $ultimo = Smtp::where('sm_id', '<>', $idReg)->orderBy('sm_id', 'desc')->first();
            if($ultimo){
                $ultimo->sm_padrao = 1;
                $ultimo->save();
            }
        }

        if($op->delete()){
            $resp['ok'] = true;
            $resp['permissao'] = true;
        }

        return response()->json($resp);

    }

}
