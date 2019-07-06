<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitePart extends Model
{
    protected $fillable = [
        'requisite_id', 'name', 'value'
    ];

    public function requisite() {
        return $this->belongsTo(Requisite::class);
    }
}
