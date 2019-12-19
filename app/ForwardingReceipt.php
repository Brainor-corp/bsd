<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForwardingReceipt extends Model
{
    protected $dates = [
        'order_date'
    ];

    public function cargo_status() {
        return $this->belongsTo(Type::class, 'cargo_status_id');
    }

    public function payment_status(){
        return $this->belongsTo(Type::class, 'payment_status_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
