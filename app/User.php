<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

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

    public function forwarding_receipts() {
        return $this->hasMany(ForwardingReceipt::class);
    }

    public function events() {
        return $this->hasMany(Event::class);
    }

    public function counterparties() {
        return $this->belongsToMany(Counterparty::class);
    }

    public function cities() {
        return $this->belongsToMany(City::class);
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
        $result = "$this->surname ";

        $result .= empty($this->name) ? '' : (mb_substr($this->name, 0, 1) . '.');
        $result .= empty($this->patronomic) ? '' : (mb_substr($this->patronomic, 0, 1) . '.');

        $limit = 7;
        $postfix = '..';

        return mb_strlen($result) > $limit ? mb_substr($result, 0, $limit) . $postfix : $result;
    }

    public function sendPasswordResetNotification($token)
    {
        // Your your own implementation.
        $this->notify(new ResetPasswordNotification($token));
    }
}
