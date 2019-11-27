<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Oversize extends Model
{
    protected $fillable = [
        'name', 'length', 'width', 'height', 'volume', 'weight', 'ratio', 'company_id'
    ];

    public function route()
    {
        return $this->hasMany(Route::class, 'oversizes_id','id');
    }

    public function getRealNameAttribute(){
        return $this->name;
    }
}
