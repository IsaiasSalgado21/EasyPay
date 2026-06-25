<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Muestra el panel de auditoría solo si el usuario es administrador.
     */
    public function index()
    {
        if (!Auth::user()->is_admin) {

        abort(403, 'Acceso denegado: No cuentas con privilegios de administrador.');
        }

        $users = DB::table('vw_safe_user_balances')->get();

        return view('admin', compact('users'));
    }
}