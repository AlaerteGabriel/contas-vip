<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use App\Repositories\UsersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{

    const PATH_VIEW = 'pages/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view(self::PATH_VIEW.'profile');
    }

    public function update(Request $request, UsersRepository $user)
    {
        $valid = $request->validate([
            'us_nome' => 'string|required',
            'us_email' => [
                'string',
                'email',
                Rule::unique('cv_users', 'us_email')->ignore($this->idUser, 'us_id'),
            ],
        ]);

        return $user->up($this->idUser, $valid);
    }

    public function passwordUpdate(Request $request, UsersRepository $user)
    {

        $valid = $request->validate([
            'current_password' => 'string|required',
            'password' => 'required|min:8|confirmed',
        ]);

        return $user->up($this->idUser, ['pass' => $valid['current_password'], 'us_password' => $valid['password']]);

    }

}
