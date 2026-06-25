<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Inyección de SQL Puro, Procedimiento Almacenado Principal
        DB::unprepared("
            CREATE PROCEDURE sp_process_transfer(
                IN p_sender_id INT,
                IN p_receiver_id INT,
                IN p_amount DECIMAL(15, 2)
            )
            BEGIN
                DECLARE current_balance DECIMAL(15, 2);
                DECLARE v_sender_wallet_id BIGINT;
                DECLARE v_receiver_wallet_id BIGINT;
                
                -- MANEJADOR DE ERRORES: Si algo falla (ej. el Trigger de saldo insuficiente, o un deadlock), 
                -- se cancela todo (ROLLBACK) y se re-lanza el error al backend.
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;s
                    RESIGNAL; 
                END;

                -- Obtener los IDs reales de las billeteras a partir de los IDs de usuario
                SELECT id INTO v_sender_wallet_id FROM wallets WHERE user_id = p_sender_id;
                SELECT id INTO v_receiver_wallet_id FROM wallets WHERE user_id = p_receiver_id;

                -- INICIO DE LA TRANSACCIÓN (ACID)
                START TRANSACTION;
                    
                    -- 1. Bloqueo Pesimista (Evita que otra petición toque el saldo al mismo tiempo)
                    SELECT balance INTO current_balance 
                    FROM wallets 
                    WHERE user_id = p_sender_id 
                    FOR UPDATE;

                    -- 2. Restar al emisor 
                    -- (Si el saldo baja de cero, el Trigger detendrá la ejecución aquí y saltará al HANDLER)
                    UPDATE wallets SET balance = balance - p_amount WHERE id = v_sender_wallet_id;
                    
                    -- 3. Sumar al receptor
                    UPDATE wallets SET balance = balance + p_amount WHERE id = v_receiver_wallet_id;
                    
                    -- 4. Registrar en transferencias (Historial)
                    INSERT INTO transactions (type, sender_wallet_id, receiver_wallet_id, amount, status, created_at, updated_at)
                    VALUES ('transfer', v_sender_wallet_id, v_receiver_wallet_id, p_amount, 'completed', NOW(), NOW());
                            
                -- Si llegamos hasta aquí sin errores, guardamos los cambios definitivamente
                COMMIT;
            END;
        ");
    }


    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_process_transfer");
    }
};