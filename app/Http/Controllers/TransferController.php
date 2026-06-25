<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class TransferController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $receiver = User::where('email', $request->email)->first();

        if ($receiver->id === auth()->id()) {
            return back()->with('error', 'No puedes transferir dinero a tu propia cuenta.');
        }

        try {
            DB::statement('CALL sp_process_transfer(?, ?, ?)', [
                auth()->id(),
                $receiver->id,
                $request->amount
            ]);

            // Si MySQL no lanza errores, significa que el COMMIT se ejecutó con éxito
            return back()->with('success', 'Transferencia completada exitosamente.');

        } catch (QueryException $e) {
            // Capturamos el error específico del Trigger (SQLSTATE 45000)
            if ($e->getCode() == '45000') {
                return back()->with('error', 'Operación denegada por la Base de Datos: Saldo insuficiente.');
            }
            
            // Capturar cualquier otro error (ej. caída de conexión)
            return back()->with('error', 'Error del sistema. La transacción fue revertida (Rollback).');
        }
    }
}