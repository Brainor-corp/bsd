<?php

namespace Bradmin\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class BRFile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mime', 'extension', 'url', 'base_url', 'path', 'size', 'title', 'alt', 'description'
    ];

    public function terms()
    {
        return $this->morphToMany('Bradmin\Cms\Models\BRTerm', 'b_r_termable', 'b_r_termables', 'b_r_termable_id', 'b_r_term_id');
    }

    public function tags()
    {
        return $this->morphToMany('Bradmin\Cms\Models\BRTag', 'b_r_termable', 'b_r_termables', 'b_r_termable_id', 'b_r_term_id')->where('type', 'tag');
    }

    public function categories()
    {
        return $this->morphToMany('Bradmin\Cms\Models\BRTerm', 'b_r_termable', 'b_r_termables', 'b_r_termable_id', 'b_r_term_id')->where('type', 'category');
    }
}
