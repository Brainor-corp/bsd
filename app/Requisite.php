<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Requisite extends Model
{
    protected $fillable = [
        'name', 'city_id', 'sort'
    ];

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function requisiteParts() {
        return $this->hasMany(RequisitePart::class);
    }

    public function scopeRegionalManager($query) {
        $user = Auth::user();
        $userCities = $user->cities;

        return $query->whereIn('city_id', count($userCities) ? $userCities->pluck('id') : []);
    }
}
