<?php

namespace Bradmin\Cms\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Kalnoy\Nestedset\NodeTrait;

class BRTag extends BRTerm
{
    protected $table = 'b_r_terms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'title', 'slug', 'description', 'parent_id', '_lft', '_rgt', 'depth', 'created_at', 'updated_at'
    ];
}
