<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutsideForwarding extends Model
{
    protected $table = 'outside_forwardings';

    public function point()
    {
        return $this->hasOne(Point::class, 'id','point');
    }

    public function forwardThreshold()
    {
        return $this->hasOne(ForwardThreshold::class, 'id','forward_threshold_id')
            ->orderBy('weight', 'ASC')
            ->orderBy('volume', 'ASC')
            ->orderBy('units', 'ASC');
    }
}
