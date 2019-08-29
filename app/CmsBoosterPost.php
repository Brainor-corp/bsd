<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Zeus\Admin\Cms\Models\ZeusAdminPost;

class CmsBoosterPost extends ZeusAdminPost
{
    protected $table = 'zeus_admin_posts';

    public function terminals() {
        return $this->belongsToMany(
            Terminal::class,
            'terminal_zeus_admin_post',
            'zeus_admin_post_id',
            'terminal_id'
        );
    }

    public function scopePosts($query)
    {
        return $query->where('type', 'news');
    }

    public function scopeRegionalManager($query) {
        $user = Auth::user();
        $userCities = $user->cities;

        return $query->whereHas('terminals', function ($terminalsQuery) use ($userCities) {
            return $terminalsQuery->whereIn('city_id', count($userCities) ? $userCities->pluck('id') : []);
        });
    }
}
