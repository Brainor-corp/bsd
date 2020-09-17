<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $fillable = [
        'title', 'route_id', 'template', 'text_1', 'text_2', 'description', 'key_words'
    ];

    use Sluggable;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'strip_tags_title'
            ]
        ];
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function getStripTagsTitleAttribute()
    {
        return strip_tags($this->title);
    }

    public function scopeOrderByUpdated($query)
    {
        return $query->orderBy('updated_at', 'DESC');
    }
}
