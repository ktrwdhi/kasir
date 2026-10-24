<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sale_detail extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }

    public function sale()
    {
        return $this->belongsTo(sales::class, 'sale_id');
    }
}
