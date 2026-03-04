<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class PasswordController extends Controller
{

    public function create(string $token, Request $request)
    {
        $email = $request->only('email');
        return view('auth/reset-password', ['tokenPass' => $token, 'email' => $email['email']]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'us_email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);


        $status = Password::broker('users')->reset(
            $request->only('us_email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'us_password' => $password
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::broker('users')::PASSWORD_RESET

                    ? redirect()->route('carteira.login')->with('success', __($status))
                    : back()->withErrors(['email' => [__($status)]]);

    }

    public function linkStore(Request $request)
    {
        $valid = $request->validate(['email' => 'required|email']);
        $status = Password::broker('users')->sendResetLink(['us_email' => $valid['email']]);

        return $status === Password::broker('users')::RESET_LINK_SENT ? back()->with(['success' => __($status)]) : back()->withErrors(['email' => __($status)]);
    }

    public function linkCreate()
    {
        return view('auth/forgot-password');
    }

}
