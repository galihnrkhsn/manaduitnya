<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasUuids;

    public const INCOME   = 1;
    public const EXPENSE  = 2;
    public const TRANSFER = 3;

    protected $table = 'transactions';
    protected $fillable = [
        'user_id',
        'wallet_id',
        'category_id',
        'type',
        'amount',
        'note',
        'transaction_date'
    ];

    protected $casts = [
        'type'              => 'integer',
        'amount'            => 'integer',
        'transaction_date'  => 'date',
    ];

    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }

    public function toWallet() {
        return $this->belongsTo(Wallet::class, 'to_wallet_id');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
