<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForwardingReceipt extends Model
{
    public function status(){
        return $this->belongsTo(Type::class, 'status_id');
    }

    public function payment_status(){
        return $this->belongsTo(Type::class, 'payment_status_id');
    }
}
