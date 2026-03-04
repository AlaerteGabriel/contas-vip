<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{

    public function index()
    {

        if(Auth::check()){

        }

        return view('auth/login');
    }

    public function logar(LoginRequest $request) : RedirectResponse
    {

        $validate = $request->validated();

        $ok = Auth::attempt([
            'us_email' => $request->safe()->login,
            'password' => $request->safe()->pass, 'us_status' => 1
        ]);

        if(!$ok){
            return back()->with('error', 'Credenciais Inválidas, revise-as');
        }

        $request->session()->regenerate();
        return redirect()->intended(route('carteira.acc.index'));
    }

    public function checkEmail(string $id, string $hash)
    {

        if(empty($id) || empty($hash)){
            return redirect()->route('carteira.login')->with('error', 'A verificação de email falhou.');
        }

        $id = Str::fromBase64($id);
        $user = User::find($id);
        if($user->us_remember_token == $hash){
            $user->email_verified_at = now();
            $user->us_status = 1;
            $user->save();
            return redirect()->route('carteira.login.index')->with('success', 'Email verificado com sucesso! Faça seu login.');
        }

    }

    public function destroy(Request $request) : RedirectResponse
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('carteira.login');
    }

}
