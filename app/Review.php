<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'author', 'email', 'phone', 'text', 'city_id'
    ];

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function file(){
        return $this->hasOne(ReviewFile::class);
    }

    public function getComprehensibleModerateAttribute(){
        return $this->moderate ? 'Да' : 'Нет';
    }
}
