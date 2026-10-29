<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';
    protected $fillable = [
        'name',
        'initial_balance'
    ];
    protected $casts = [
        'initial_balance'   => 'integer',
        'archived_at'       => 'datetime'
    ];

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function incomingTransfers() {
        return $this->hasMany(Transaction::class, 'to_wallet_id');
    }

    public function scopeActive($query) {
        return $query->whereNull('archived_at');
    }

    public function balance(): int {
        $income     = (int) $this->transactions()->where('type', Transaction::INCOME)->sum('amount');
        $expense    = (int) $this->transactions()->where('type', Transaction::EXPENSE)->sum('amount');
        $out        = (int) $this->transactions()->where('type', Transaction::TRANSFER)->sum('amount');
        $in         = (int) $this->incomingTransfers()->sum('amount');

        return $this->initial_balance + $income - $expense - $out + $in;
    }
}
