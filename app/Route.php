<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = [
        'name',
        'ship_city_id',
        'dest_city_id',
        'min_cost',
        'delivery_time',
        'base_route',
        'addition',
        'oversizes_id',
        'wrapper_tariff',
        'fixed_tariffs',
        'coefficient',
        'show_in_price',
    ];

    public function shipCity()
    {
        return $this->hasOne(City::class, 'id','ship_city_id');
    }

    public function destinationCity()
    {
        return $this->hasOne(City::class, 'id','dest_city_id');
    }

    public function anton_oversize()
    {
        return $this->hasOne(City::class, 'id','oversizes_id');
    }

    public function oversize()
    {
        return $this->hasOne(Oversize::class, 'id','oversizes_id');
    }

    public function route_tariffs()
    {
        return $this->hasMany(RouteTariff::class);
    }

    public function getComprehensiveShowInPriceAttribute()
    {
        return $this->show_in_price ? 'Да' : 'Нет';
    }

    public function getDashNameAttribute()
    {
        return str_replace(' → ', '-', $this->name);
    }

    public function getDashNameWithIdAttribute()
    {
        return "$this->id. $this->dash_name";
    }

    public function getRealArrowNameAttribute()
    {
        if($this->destinationCity->is_ship) {
            return str_replace('→', '↔', $this->name);
        }

        return $this->name;
    }
}
