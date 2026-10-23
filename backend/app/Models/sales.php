<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sales extends Model
{
    protected $fillable = [
        'user_id',
        'outlet_id',
        'invoice',
        'total',
        'payment',
        'status',
        'cash',
        'change'
    ];
}
