<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kladr extends Model
{
    protected $table = 'kladr';

    public function city()
    {
        return $this->hasOne(City::class, 'kladr_id','id');
    }

}
