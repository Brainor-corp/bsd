<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Counterparty extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function type() {
        return $this->belongsTo(Type::class);
    }

    public function getTitleAttribute() {
        return $this->name ?? $this->company_name;
    }

    public function getActiveForHumanAttribute() {
        return $this->active ? 'Активен' : 'Не активен';
    }
}
