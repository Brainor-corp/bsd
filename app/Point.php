<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $fillable = [
        'name', 'region_code', 'city_id', 'distance',
    ];

    public function city()
    {
        return $this->hasOne(City::class, 'id','city_id');
    }

//    public function anton_region()
//    {
//        return $this->hasOne(ForwardThreshold::class, 'code','region_code');
//    }

    public function outsideForwarding() {
        return $this->hasMany(OutsideForwarding::class, 'point', 'id');
    }

    public function region(){
        return $this->hasOne(Region::class, 'code', 'region_code');
    }

}
