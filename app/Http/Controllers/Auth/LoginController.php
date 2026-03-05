<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        return redirect()->intended(route('dashboard.index'));
    }

    public function destroy(Request $request) : RedirectResponse
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

}
