<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'email', 'fio', 'ip', 'text', 'rating', 'visible', 'moderate',
    ];

    public function status(){
        return $this->belongsTo(Type::class, 'status_id');
    }

    public function order_items(){
        return $this->hasMany(OrderItem::class);
    }

    public function getRealStatusAttribute(){
        return $this->status->name ?? '';
    }

    public function getComprehensibleTakeNeed(){
        return $this->take_need ? 'Да' : 'Нет';
    }

    public function getComprehensibleDeliveryNeed(){
        return $this->delivery_need ? 'Да' : 'Нет';
    }
}
