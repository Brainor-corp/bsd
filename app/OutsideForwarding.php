<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutsideForwarding extends Model
{
    protected $table = 'outside_forwardings';
    protected $fillable = [
        'point', 'forward_threshold_id', 'tariff', 'max_dimension_id'
    ];

//    public function point()
//    {
//        return $this->hasOne(Point::class, 'id','point');
//    }
    public function point_relation() // эта связь для отображения в админке, т.к. поле названо не points_id, а points
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
