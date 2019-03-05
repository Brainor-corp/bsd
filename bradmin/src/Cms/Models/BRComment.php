<?php

namespace Bradmin\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class BRComment extends Model
{
    protected $fillable = [
        'id', 'user_id', 'email', 'fio', 'ip', 'text', 'rating', 'visible', 'moderate'
    ];
}
