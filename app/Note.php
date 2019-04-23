<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'user_id', 'email', 'fio', 'ip', 'text', 'rating', 'visible', 'moderate',
    ];

    public function type(){
        return $this->belongsTo(Type::class);
    }

    public function getRealTypeAttribute(){
        return $this->type->name;
    }
}
