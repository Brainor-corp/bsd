<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'name', 'code', 'dest_city_id', 'threshold_group_id', 'tariff_zone_id', 'fixed_tariff', 'dist_tariff', 'inside_tariff',
    ];

    public function destinationCity()
    {
        return $this->hasOne(City::class, 'id','dest_city_id');
    }

    public function thresholdGroup()
    {
        return $this->hasOne(Type::class, 'id','threshold_group_id');
    }

    public function tariffZone()
    {
        return $this->hasOne(Type::class, 'id','tariff_zone_id');
    }

    public function getComprehensiveThresholdGroupAttribute(){
        return $this->thresholdGroup->name;
    }

    public function getComprehensiveTariffZoneAttribute(){
        return $this->tariffZone->name;
    }

    public function getComprehensiveDestinationCityAttribute(){
        return $this->destinationCity->name ?? 'Не указан';
    }
}
