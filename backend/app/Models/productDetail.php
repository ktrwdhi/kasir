<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class productDetail extends Model
{
    protected $fillable = [
        'product_id',
        'product_varian_id',
        'outlet_id',
        'price',
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

    public function varians()
    {
        return $this->belongsTo(productVarian::class, 'product_varian_id');
    }
}
