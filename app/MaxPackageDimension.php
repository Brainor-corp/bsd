<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaxPackageDimension extends Model
{
    protected $fillable = [
        'name',
        'length',
        'width',
        'height'
    ];
}
