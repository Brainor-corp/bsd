<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name', 'is_ship', 'is_filial', 'is_popular', 'doorstep', 'tariff_zone_id', 'threshold_group_id', 'message'
    ];

    use Sluggable;
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function shipCityRoutes()
    {
        return $this->hasMany(Route::class, 'ship_city_id','id');
    }

//    public function destinationCityRoutes()
//    {
//        return $this->hasMany(City::class, 'dest_city_id','id');
//    }

    public function thresholdGroup() {
        return $this->hasOne(Type::class, 'id', 'threshold_group_id');
    }

    public function insideForwarding() {
        return $this->hasMany(InsideForwarding::class);
    }

    public function tariffZone(){
        return $this->hasOne(Type::class, 'id', 'tariff_zone_id');
    }

    public function terminal() {
        return $this->hasOne(Terminal::class, 'city_id', 'id');
    }

    public function terminals() {
        return $this->hasMany(Terminal::class);
    }

    public function kladr() {
        return $this->hasOne(Kladr::class, 'id', 'kladr_id');
    }

    public function getComprehensiveIsShipAttribute(){
        return $this->is_ship ? 'Да' : 'Нет' ;
    }

    public function getComprehensiveIsFilialAttribute(){
        return $this->is_filial ? 'Да' : 'Нет' ;
    }

    public function getComprehensiveDoorstepAttribute(){
        return $this->doorstep ? 'Да' : 'Нет' ;
    }

    public function getCoordinatesOrAddressAttribute() {
        $result = '';

        if(isset($this->terminal)) {
            $result = $this->terminal->revert_geo_point ?? $this->terminal->address;
            $result = addcslashes($result, '"');
        }

        if(empty(trim($result))) {
            $result = $this->name;
        }

        return $result;
    }
}
