<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['deposit', 'withdrawal', 'transfer']);
            
            $table->foreignId('sender_wallet_id')->nullable()->constrained('wallets')->onDelete('set null');
            $table->foreignId('receiver_wallet_id')->nullable()->constrained('wallets')->onDelete('set null');
            
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            
            $table->timestamps();
            
            // Índices para optimizar las búsquedas cuando el usuario vea su historial
            $table->index('sender_wallet_id');
            $table->index('receiver_wallet_id');
        });
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};