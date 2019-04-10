<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerKmTariff extends Model
{
    protected $table = 'per_km_tariffs';

    public function forwardThreshold()
    {
        return $this->hasOne(ForwardThreshold::class, 'id','forward_threshold_id');
    }

    public function tariffZone()
    {
        return $this->hasOne(Type::class, 'id','tariff_zone_id');
    }
}
