<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
abstract class Controller
{
    //
    use AuthorizesRequests; // Adicione esta linha

    public $msgSuccess = 'Informações salvas com sucesso!!';
    public $msgError = 'Falha ao salvar informações, tente novamente.';

    public function __construct()
    {

    }

}
