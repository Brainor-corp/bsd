<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oversize extends Model
{
    public function route()
    {
        return $this->hasMany(Route::class, 'oversizes_id','id');
    }
}
