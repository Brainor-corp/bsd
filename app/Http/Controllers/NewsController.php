<?php

namespace App\Http\Controllers;

use Bradmin\Cms\Helpers\CMSHelper;
use Bradmin\Cms\Models\BRPost;
use Bradmin\Cms\Models\BRTag;
use Bradmin\Cms\Models\BRTerm;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function showList() {
        $args = [
            'term' => ['novosti'],
            'type' => 'post',
        ];
        $posts = CMSHelper::getQueryBuilder($args)
            ->with(
                [
                    'terms' => function ($term){
                        return $term->where('slug', '<>', 'novosti');
                    },
                    'tags',
                ]
            )->paginate(1);

        $cityRootTag = BRTag::where([['type', 'tag'], ['slug', 'gorod']])->first();
        $cityTags = BRTag::whereDescendantOf($cityRootTag)->get();

        $newsRootTerm = BRTerm::where([['type', 'category'], ['slug', 'novosti']])->first();
        $newsTerms = BRTerm::whereDescendantOf($newsRootTerm)->get();

        return view('v1.pages.news.list.list')->with(compact('posts', 'cityTags', 'newsTerms'));
    }

    public function filterAction(Request $request){
        return $request->all();
    }
}
