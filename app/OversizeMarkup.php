<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OversizeMarkup extends Model
{
    protected $table = 'oversize_markups';
    protected $fillable = [
        'oversize_id', 'rate_id', 'value', 'threshold', 'markup'
    ];

    public function rate(){
        return $this->belongsTo(Type::class, 'rate_id');
    }

    public function oversize(){
        return $this->belongsTo(Oversize::class);
    }

}
