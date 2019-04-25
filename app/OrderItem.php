<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'length', 'width', 'height', 'volume', 'quantity', 'weight'
    ];

    public function type(){
        return $this->belongsTo(Type::class);
    }
}
