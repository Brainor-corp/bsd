<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id', 'email', 'fio', 'ip', 'text', 'rating', 'visible', 'moderate',
    ];


    public function getRealModerateAttribute(){
        return $this->moderate ? 'Да' : 'Нет' ;
    }

    public function getRealVisibleAttribute(){
        return $this->visible ? 'Да' : 'Нет';
    }
}
