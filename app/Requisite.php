<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
