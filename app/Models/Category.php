<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'type',
        'color'
    ];

    protected $casts = [
        'type'          => 'integer',
        'archived_at'   => 'datetime'
    ];

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }
}
