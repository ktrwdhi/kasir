<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class outlet extends Model
{
    protected $fillable = [
        'name',
        'address',
    ];

    public function products()
    {
        return $this->hasMany(products::class);
    }
}
