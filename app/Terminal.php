<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    protected $fillable = [
        'name', 'short_name', 'city_id', 'region_code', 'street', 'house', 'geo_point',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_code', 'code');
    }

    public function getRealCityAttribute(){
        return $this->city->name;
    }

    public function getRealRegionAttribute(){
        return $this->region->name ?? '';
    }
}
