<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

interface UsersInterface
{
    //
    public function add(array $attr, $tipoRetorno = 'redirect') : User|Bool|RedirectResponse;
    public function up(int $id, array $attr) : bool|RedirectResponse;
}
