<?php

namespace App\Http\Controllers;

use App\Imports\DadosImport;
use App\Models\Configuracoes;
use App\Models\Smtp;
use App\Models\TemplatesEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ConfiguracoesController extends Controller
{

    const PATH_VIEW = 'pages/settings/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {

        // 1. Validação dos dados recebidos do formulário
        $validatedData = $request->validate([
            'mail_from_name'    => 'required|string|max:100',
            'mail_from_address' => 'required|email|max:50',
            'mail_host'         => 'required|string|max:50',
            'mail_port'         => 'required|integer',
            'mail_encryption'   => 'required|in:none,tls,ssl',
            'mail_username'     => 'required|string|max:50',
            'mail_password'     => 'nullable|string', // Nullable caso a pessoa mande vazio para manter a senha atual
        ]);

        // Só atualiza a senha se o usuário digitou uma nova
        if (empty($validatedData['mail_password'])) {
            unset($validatedData['mail_password']);
        }

        // 3. Faz o INSERT ou UPDATE no banco de dados
        DB::beginTransaction();

        try {

            foreach ($validatedData AS $key => $value) {
                Configuracoes::updateOrCreate(
                    ['co_key' => $key],
                    ['co_value' => $value, 'co_group' => 'smtp']
                );
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao salvar configurações do servidor de email.')->withInput();
        }
        // OPCIONAL: Limpa o cache se você armazena essas configs em cache
        // Cache::forget('mail_settings');
        // 4. Retorna com mensagem de sucesso
        return back()->with('success', 'Servidor de envio SMTP atualizado com sucesso!');
    }

    public function emailMarketing()
    {
        return view(self::PATH_VIEW.'email-marketing');
    }

    public function emailMarketingStore(Request $request)
    {
    }

    public function importar()
    {
        return view(self::PATH_VIEW.'createImport');
    }

    public function produtosStoreCsv(Request $request)
    {
        $file = request()->file('file'); // Arquivo enviado pelo formulário
        Excel::import(new DadosImport, $file, null, \Maatwebsite\Excel\Excel::XLSX);
        return response()->json(['ok' => 1]);
    }


}
