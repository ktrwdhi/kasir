<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    protected $fillable = [
        'outlet_id',
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];

    public function outlet(){
        return $this->belongsTo(outlet::class,'outlet_id');
    }

    public function category(){
        return $this->belongsTo(category::class,'category_id');
    }
}
