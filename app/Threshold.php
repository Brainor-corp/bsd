<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{

    protected $fillable = [
        'rate_id', 'value'
    ];
    public function rate()
    {
        return $this->hasOne(Type::class, 'id','rate_id');
    }

    public function getThresholdRateValueAttribute(){
        return $this->rate->name . ' - ' . $this->value;
    }
}
