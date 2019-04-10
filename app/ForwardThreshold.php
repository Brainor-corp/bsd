<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForwardThreshold extends Model
{
    protected $table = 'forward_thresholds';

    public function thresholdGroup()
    {
        return $this->hasOne(Type::class, 'id','threshold_group_id');
    }
}
