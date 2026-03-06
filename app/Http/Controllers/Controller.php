<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    //
    use AuthorizesRequests; // Adicione esta linha
    public $registrosPorPagina = 10;
    protected $idUser;
    public $msgSuccess = 'Informações salvas com sucesso!!';
    public $msgError = 'Falha ao salvar informações, tente novamente.';

    public function __construct()
    {
        Paginator::useBootstrapFive();
        $this->idUser = Auth::user()->us_id;
    }

}
