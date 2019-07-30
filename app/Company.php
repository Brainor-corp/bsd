<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name'
    ];

    public function oversizes() {
        return $this->hasMany(Oversize::class);
    }

    public function thresholds() {
        return $this->hasMany(Threshold::class);
    }
}
