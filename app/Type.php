<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use Sluggable;

    protected $fillable = [
        'name', 'class'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function scopeTariffZones($query)
    {
        return $query->where('class', 'tariff_zones');
    }
}
