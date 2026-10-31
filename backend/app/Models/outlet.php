<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class outlet extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'address',
    ];

    public function productDetails()
    {
        return $this->hasMany(productDetail::class,'outlet_id');
    }
}
