<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = [
        'fio', 'phone', 'text', 'company_name', 'subject_id',
    ];

    public function subject(){
        return $this->belongsTo(Type::class, 'subject_id');
    }

}
