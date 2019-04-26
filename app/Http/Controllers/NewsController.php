<?php

namespace App\Http\Controllers;

use Bradmin\Cms\Helpers\CMSHelper;
use Bradmin\Cms\Models\BRPost;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function showList() {
//        $posts = BRPost::all();
        $args = [
            'term' => ['novosti'],
            'type' => 'post'
        ];
        $posts = CMSHelper::getQueryBuilder($args)->with(['terms' => function ($term){
            return $term->where('slug', '<>', 'novosti');
        }])->get();

        return view('v1.pages.news.list.list')->with(compact('posts'));
    }
}
