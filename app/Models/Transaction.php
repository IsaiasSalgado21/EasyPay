<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'sender_wallet_id',
        'receiver_wallet_id',
        'amount',
        'status'
    ];

    // Relación: Una transacción tiene un emisor (que es una billetera)
    public function sender()
    {
        return $this->belongsTo(Wallet::class, 'sender_wallet_id');
    }

    // Relación: Una transacción tiene un receptor (que es una billetera)
    public function receiver()
    {
        return $this->belongsTo(Wallet::class, 'receiver_wallet_id');
    }
}