<?php

namespace App;

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
}
