<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactEmail extends Model
{
    protected $fillable = [
        'email', 'active', 'description',
    ];

    public function getComprehensibleActiveAttribute(){
        return $this->active ? 'Да' : 'Нет';
    }
}
