<?php

namespace App\Http\Helpers\Posts\Helpers;

namespace Bradmin\Cms\Helpers;

use Bradmin\Cms\Models\BRPost;

class CMSHelper
{
    /**
     * @param $args = [
     *      'category' => 'cat1', 'cat2', etc
     *      'tags' => 'tag1', 'tag2', etc
     *      'order_by' => ['field', 'desk']
     *      'slug' => 'slug'
     *      'type' => 'post' // or 'page'
     * ]
     * @return mixed
     */
    public static function getQueryBuilder($args)
    {
        return BRPost::with(
            array(
                'categories' => function ($query) {
                    $query->categories();
                },
                'tags' => function ($query) {
                    $query->tags();
                },
            )
        )
            ->when(
                isset($args['category']),
                function ($query) use ($args) {
                    return $query->whereHas(
                        'terms',
                        function ($term) use ($args) {
                            $term->categories()->whereIn('slug', $args['category']);
                        }
                    );
                }
            )
            ->when(
                isset($args['tags']),
                function ($query) use ($args) {
                    return $query->whereHas(
                        'terms',
                        function ($term) use ($args) {
                            $term->tags()->whereIn('slug', $args['tags']);
                        }
                    );
                }
            )
            ->when(
                isset($args['order_by']),
                function ($query) use ($args) {
                    return $query->orderBy($args['order_by'][0], $args['order_by'][1]);
                }
            )
            ->when(
                isset($args['slug']),
                function ($query) use ($args) {
                    return $query->where('slug', $args['slug']);
                }
            )
            ->when(
                isset($args['type']),
                function ($query) use ($args) {
                    return $query->where('type', $args['type']);
                }
            )
            ->where('status', 'published');
    }

    public static function getByUrl($url) {
        return BRPost::where('url',$url)->first();
    }
}
