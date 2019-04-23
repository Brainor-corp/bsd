<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerKmTariff extends Model
{
    protected $table = 'per_km_tariffs';
    protected $fillable = [
        'tariff', 'forward_threshold_id', 'tariff_zone_id'
    ];

    public function forwardThreshold()
    {
        return $this->belongsTo(ForwardThreshold::class, 'forward_threshold_id','id');
    }

    public function tariffZone()
    {
        return $this->hasOne(Type::class, 'id','tariff_zone_id');
    }

}
