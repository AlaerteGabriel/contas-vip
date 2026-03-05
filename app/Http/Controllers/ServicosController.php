<?php

namespace App\Http\Controllers;

use App\Models\Servicos;

class ServicosController extends Controller
{

    const PATH_VIEW = 'pages/servicos/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = Servicos::all();
        return view(self::PATH_VIEW.'index', ['servicos' => $all]);
    }

    public function create()
    {

    }

    public function store()
    {

    }

}
