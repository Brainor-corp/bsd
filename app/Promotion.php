<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'title', 'text', 'start_at', 'end_at', 'amount'
    ];

    public function getCStartAtAttribute()
    {
        return Carbon::parse($this->start_at);
    }

    public function getCEndAtAttribute()
    {
        return Carbon::parse($this->end_at);
    }
}
