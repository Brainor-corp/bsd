<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsideForwarding extends Model
{
    protected $table = 'inside_forwarding';
    protected $fillable = [
        'city_id', 'forward_threshold_id', 'tariff', 'max_dimension_id'
    ];

    public function city() {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function forwardThreshold()
    {
        return $this->hasOne(ForwardThreshold::class, 'id','forward_threshold_id')
            ->orderBy('weight', 'ASC')
            ->orderBy('volume', 'ASC')
            ->orderBy('units', 'ASC');
    }

}
