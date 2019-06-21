<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    public function terms() {
        return $this->morphToMany('Zeus\Admin\Cms\Models\ZeusAdminTerm', 'zeus_admin_termable', 'zeus_admin_termables', 'zeus_admin_termable_id', 'zeus_admin_term_id');
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
