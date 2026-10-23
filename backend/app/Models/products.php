<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    protected $fillable = [
        'outlet_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];
}
