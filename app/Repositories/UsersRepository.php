<?php
namespace App\Repositories;

use AllowDynamicProperties;
use App\Models\User;
use App\Helpers\UtilHelper;
use App\Interfaces\UsersInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

#[AllowDynamicProperties]
class UsersRepository implements UsersInterface
{

    public function __construct()
    {
        // Aqui o PHP já permite lógica dinâmica
        $this->idUser = auth()->id();
    }

    public function add(array $attr, $tipoRetorno = 'redirect') : User|Bool|RedirectResponse
    {

        DB::beginTransaction();
        $resp = false;

        try {

            $attr['us_remember_token'] = Str::random(10);
            $user = User::firstOrCreate(['us_email' => $attr['us_email']], $attr);

            // Operação é concluída com êxito
            DB::commit();

            if(isset($attr['mailme']) && $attr['mailme'] == 'S'){
                $attr['us_id'] = $user->us_id;
                $attr['us_remember_token'] = $user->us_remember_token;
            }

            if($tipoRetorno == 'redirect'){
                return back()->withInput()->with('success', '
                    Cadastro realizado com sucesso! Você receberá um link de confirmação de cadastro no email informado.
                    É de suma importância a confirmação de seu cadastro para garantir a integridade das informações aqui declaradas.
                    Após essa confirmação, faça seu login.
                ');
            }

            $resp = $user;

        }catch(\Exception $e){

            // Salvar log
            Log::info('Cliente não adicionado', ['error' => $e->getMessage()]);

            // Operação não é concluída com êxito
            DB::rollBack();

            if($tipoRetorno == 'redirect'){
                return back()->withInput()->with('error', 'Houve uma falha ao tentar realizar seu cadastro, tenta novamente ou entre em contato com nosso time de suporte.');
            }
        }

        return $resp;
    }

    public function up(int $idUser, array $attr): bool|RedirectResponse
    {


        DB::beginTransaction();

        try {

            array_filter($attr);

            if(isset($attr['us_password']) && !empty($attr['us_password'])){
                if(isset($attr['pass']) && !empty($attr['pass']) && Hash::check($attr['pass'], Auth::user()->us_password)){
                    $attr['us_password'] = Hash::make($attr['us_password']);
                }else{
                    //Auth::logoutOtherDevices($attr['pass']);
                    return back()->withInput()->with('error', 'A senha atual está incorreta!');
                }
            }

            User::updateOrCreate(
                ['us_id' => $idUser ?? $this->idUser],
                $attr
            );

            // Operação é concluída com êxito
            DB::commit();

            return back()->withInput()->with('success', 'Usuário atualizado com sucesso!');

        }catch(\Exception $e){

            // Salvar log
            Log::error('Usuário não alterado', ['error' => $e->getMessage()]);

            // Operação não é concluída com êxito
            DB::rollBack();

            // Redirecionar o usuário, enviar a mensagem de erro
            return back()->withInput()->with('error', 'O usuário não pode ser alterado');
        }

    }

}
