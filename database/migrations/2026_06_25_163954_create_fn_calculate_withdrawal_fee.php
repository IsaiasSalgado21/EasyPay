<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Función que calcula el neto a recibir tras descontar el 3% de comisión
        DB::unprepared("
            CREATE FUNCTION fn_calculate_withdrawal_fee(p_amount DECIMAL(15,2)) 
            RETURNS DECIMAL(15,2)
            DETERMINISTIC
            BEGIN
                DECLARE v_fee DECIMAL(15,2);
                SET v_fee = p_amount * 0.03; -- 3% de comisión
                RETURN p_amount - v_fee;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_calculate_withdrawal_fee");
    }
};