<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function shipCityRoutes()
    {
        return $this->hasMany(Route::class, 'ship_city_id','id');
    }

    public function destinationCityRoutes()
    {
        return $this->hasMany(City::class, 'dest_city_id','id');
    }
}
