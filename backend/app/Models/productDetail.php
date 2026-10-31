<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class productDetail extends Model
{
    protected $fillable = [
        'product_id',
        'outlet_id',
        'stock'
    ];

    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }

    public function outlet()
    {
        return $this->belongsTo(outlet::class, 'outlet_id');
    }
}
