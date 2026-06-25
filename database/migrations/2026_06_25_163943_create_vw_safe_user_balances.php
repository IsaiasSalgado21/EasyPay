<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_calculate_withdrawal_fee;");


        DB::unprepared("
            CREATE FUNCTION fn_calculate_withdrawal_fee(p_amount DECIMAL(15,2)) 
            RETURNS DECIMAL(15,2)
            DETERMINISTIC
            BEGIN
                DECLARE v_fee_percentage DECIMAL(5,2) DEFAULT 0.03; -- 3% de comisión
                DECLARE v_net_amount DECIMAL(15,2);
                
                -- Cálculo matemático dentro de MySQL
                SET v_net_amount = p_amount - (p_amount * v_fee_percentage);
                
                RETURN v_net_amount;
            END;
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS fn_calculate_withdrawal_fee;");
    }
};