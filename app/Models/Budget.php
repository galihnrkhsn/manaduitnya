<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $table = 'budgets';
    protected $guarded = [];
    protected $fillable = [
        'category_id',
        'month',
        'amount',
        'user_id'
    ];

    protected $casts = [
        'month'     => 'date',
        'amount'    => 'integer'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
