<?php

namespace App;

use Bradmin\Cms\Models\BRTag;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class CustomTag extends BRTag
{
    public function files()
    {
        return $this->morphedByMany('Bradmin\Cms\Models\BRFile', 'b_r_termable', 'b_r_termables', 'b_r_term_id', 'b_r_termable_id');
    }
}
