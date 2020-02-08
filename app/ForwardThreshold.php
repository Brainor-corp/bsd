<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForwardThreshold extends Model
{
    protected $table = 'forward_thresholds';
    protected $fillable = [
        'name', 'weight', 'volume', 'units', 'real_threshold', 'length', 'width', 'height'
    ];

    public function thresholdGroup()
    {
        return $this->hasOne(Type::class, 'id','threshold_group_id');
    }

}
