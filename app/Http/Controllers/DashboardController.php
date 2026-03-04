<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    const PATH_VIEW = 'dashboard/';


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return view(self::PATH_VIEW.'index', []);
    }

}
