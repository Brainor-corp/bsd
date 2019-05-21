<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;

class Promotion extends Model
{
    use Sluggable;

    protected $fillable = [
        'title', 'slug', 'text', 'start_at', 'end_at', 'amount'
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getCStartAtAttribute()
    {
        return Carbon::parse($this->start_at);
    }

    public function getCEndAtAttribute()
    {
        return Carbon::parse($this->end_at);
    }
}
