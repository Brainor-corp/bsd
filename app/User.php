<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'patronomic', 'phone', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsToMany(Role::class);
    }

    public function getFullNameAttribute(){
        return implode(' ', [
            $this->surname,
            $this->name,
            $this->patronomic,
        ]);
    }
    public function getSurnameInitialsAttribute(){
        return implode(' ', [
            $this->surname,
            $this->name[0] . '.' . $this->patronomic[0] . '.',
        ]);
    }
}
