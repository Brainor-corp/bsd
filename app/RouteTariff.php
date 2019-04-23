<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RouteTariff extends Model
{
    protected $table = 'route_tariffs';
    protected $fillable = [
        'route_id', 'rate_id', 'threshold_id', 'price',
    ];

    public function route()
    {
        return $this->hasOne(Route::class, 'id','route_id');
    }

    public function rate()
    {
        return $this->hasOne(Type::class, 'id','rate_id');
    }

    public function threshold()
    {
        return $this->hasOne(Threshold::class, 'id','threshold_id')->orderBy('value');
    }

}
