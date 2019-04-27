<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'title', 'text', 'start_at', 'end_at', 'amount'
    ];

    public function getCStartAtAttribute($date)
    {
        return Carbon::parse($date);
    }

    public function getCEndAtAttribute($date)
    {
        return Carbon::parse($date);
    }
}
