<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    public function shipCity()
    {
        return $this->hasOne(City::class, 'id','ship_city_id');
    }

    public function destinationCity()
    {
        return $this->hasOne(City::class, 'id','dest_city_id');
    }

    public function oversize()
    {
        return $this->hasOne(City::class, 'id','oversizes_id');
    }
}
