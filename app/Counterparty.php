<?php

namespace App;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;

class Counterparty extends Model
{
    use Encryptable;

    protected $encryptable = [
        'legal_form',
        'company_name',
        'legal_address_city',
        'legal_address',
        'inn',
        'kpp',
        'phone',
        'name',
        'passport_series',
        'passport_number',
        'addition_info',
        'contact_person',
    ];

    protected $fillable = [
        'code_1c',
        'active',
        'type_id',
        'legal_form',
        'company_name',
        'legal_address_city',
        'legal_address_street',
        'legal_address_house',
        'legal_address_block',
        'legal_address_building',
        'legal_address_apartment',
        'inn',
        'kpp',
        'phone',
        'name',
        'passport_series',
        'passport_number',
        'addition_info',
        'contact_person',
        'hash_name',
        'hash_inn'
    ];

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
