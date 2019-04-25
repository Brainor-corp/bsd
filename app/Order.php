<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'shipping_name', 'total_weight', 'status_id', 'ship_city_id', 'ship_city_name', 'dest_city_id', 'dest_city_name',
        'take_need', 'take_in_city', 'take_address', 'take_distance', 'take_point',
        'take_time', 'take_price', 'delivery_need', 'delivery_in_city', 'delivery_address',
        'delivery_distance', 'delivery_point', 'delivery_price', 'delivery_time', 'delivered_in',
        'total_price', 'sender_id', 'sender_name', 'sender_phone', 'recipient_id',
        'recepient_name', 'recepient_phone', 'payer_id', 'payer_name', 'payer_phone',
        'payment_type', 'code_1c', 'manager_id', 'operator_id', 'order_date',
        'order_finish_date', 'discount', 'discount_amount', 'insurance', 'insurance_amount'
    ];

    public function status(){
        return $this->belongsTo(Type::class, 'status_id');
    }
    public function payment(){
        return $this->belongsTo(Type::class, 'payment_type');
    }
    public function payer(){
        return $this->belongsTo(Type::class, 'payer_type');
    }

    public function order_items(){
        return $this->hasMany(OrderItem::class);
    }

    public function order_services(){
        return $this->belongsToMany(Service::class);
    }

    public function ship_city(){
        return $this->belongsTo(City::class, 'ship_city_id');
    }
    public function dest_city(){
        return $this->belongsTo(City::class, 'dest_city_id');
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
