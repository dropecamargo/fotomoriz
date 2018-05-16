<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Instantiate a new Controller instance.
     */
    public function __construct()
    {
        $this->middleware('ability:admin,consultar');
        $this->middleware('ability:admin,crear', ['only' => ['create', 'store']]);
        $this->middleware('ability:admin,editar', ['only' => ['edit', 'update']]);
        $this->middleware('ability:admin,eliminar', ['only' => ['destroy']]);
    }
}
