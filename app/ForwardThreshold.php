<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForwardThreshold extends Model
{
    protected $table = 'forward_thresholds';
    protected $fillable = [
        'name',
        'name_params',
        'name_dimensions',
        'weight',
        'volume',
        'units',
        'real_threshold',
        'length',
        'width',
        'height',
        'threshold_group_id'
    ];

    public function thresholdGroup()
    {
        return $this->hasOne(Type::class, 'id','threshold_group_id');
    }

    public function getVolumeAttribute($value)
    {
        return isset($value) ? $value + 0 : null; // + 0 убирает лишние нули после запятой
    }

    public function getLengthAttribute($value)
    {
        return isset($value) ? $value + 0 : null; // + 0 убирает лишние нули после запятой
    }

    public function getWidthAttribute($value)
    {
        return isset($value) ? $value + 0 : null; // + 0 убирает лишние нули после запятой
    }

    public function getHeightAttribute($value)
    {
        return isset($value) ? $value + 0 : null; // + 0 убирает лишние нули после запятой
    }
}
