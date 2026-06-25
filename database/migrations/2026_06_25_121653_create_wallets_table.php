<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->string('currency', 3)->default('USD');
            $table->timestamps();
        });

        // Inyeccion de SQL Puro, Trigger de Protección de Saldo
        // Si por algún motivo el backend intenta dejar la cuenta en negativo, MySQL abortará la operación.

        DB::unprepared("
            CREATE TRIGGER trg_before_wallet_update
            BEFORE UPDATE ON wallets
            FOR EACH ROW
            BEGIN
                IF NEW.balance < 0 THEN
                    SIGNAL SQLSTATE '45000' 
                    SET MESSAGE_TEXT = 'Error: Saldo insuficiente para realizar la transacción.';
                END IF;
            END;
        ");
    }


    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_before_wallet_update");
        Schema::dropIfExists('wallets');
    }
};