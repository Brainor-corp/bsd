<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Polygon extends Model
{
    protected $fillable = [
        'name', 'coordinates', 'price', 'city_id', 'priority'
    ];

    public function city() {
        return $this->belongsTo(City::class);
    }
}
