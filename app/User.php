<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable; // Не удалять. Нужно для отправки email восстановления пароля.

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'patronomic', 'phone', 'email', 'password', 'phone_verification_code', 'code_send_at', 'guid', 'sync_need'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'code_send_at'
    ];

    public $zeusAdminIgnore = [
        'notifications', 'readNotifications', 'unreadNotifications'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function events() {
        return $this->hasMany(Event::class);
    }

    public function hasRole($slug){
        return $this->roles()->where('slug', $slug)->exists() ? true : false;
    }

    public function hasRoleWithPermission($slug){
        return $this->roles()->whereHas('permissions', function ($permission) use ($slug) {
            return $permission->whereSlug($slug);
        })->exists() ? true : false;
    }

    public function isSuperAdmin() {
        return $this->hasRole('super-admin');
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
