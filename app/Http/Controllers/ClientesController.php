<?php

namespace App\Http\Controllers;

use App\Models\Clientes;

class ClientesController extends Controller
{

    const PATH_VIEW = 'pages/clientes/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = Clientes::all();
        return view(self::PATH_VIEW.'index', ['clientes' => $all]);
    }

    public function create()
    {

    }

    public function store()
    {

    }

}
