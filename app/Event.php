<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id', 'email', 'fio', 'ip', 'text', 'rating', 'visible', 'moderate',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

}
