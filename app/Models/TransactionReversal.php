<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionReversal extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'original_transaction_id',
        'reversed_by',
        'reason',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // RELACIONAMENTOS
    public function originalTransaction()
    {
        return $this->belongsTo(Transaction::class, 'original_transaction_id');
    }

    public function reversedBy()
    {
        return $this->belongsTo(User::class, 'reversed_by');
    }
}

