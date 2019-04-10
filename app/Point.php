<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    public function city()
    {
        return $this->hasOne(City::class, 'id','city_id');
    }

    public function region()
    {
        return $this->hasOne(ForwardThreshold::class, 'code','region_code');
    }
}
