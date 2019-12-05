<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForwardingReceipt extends Model
{
    protected $dates = [
        'order_date'
    ];

    public function status(){
        return $this->belongsTo(Type::class, 'status_id');
    }

    public function cargo_status(){
        return $this->belongsTo(Type::class, 'cargo_status_id');
    }
}
