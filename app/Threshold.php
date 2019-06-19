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
        $rate = '';

        switch($this->rate->slug) {
            case "ves": $rate = 'кг'; break;
            case "obem": $rate = 'куб. м.'; break;
            case "lineynyy-razmer": $rate = '(лин. размер)'; break;
        }

        return "$this->value $rate";
    }
}
