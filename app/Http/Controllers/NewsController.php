<?php

namespace App\Http\Controllers;

use Bradmin\Cms\Helpers\CMSHelper;
use Bradmin\Cms\Models\BRPost;
use Bradmin\Cms\Models\BRTag;
use Bradmin\Cms\Models\BRTerm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class NewsController extends Controller {
    public function showList(Request $request) {
        $args = [
            'term' => ['novosti'],
            'type' => 'post',
            'published' => true,
        ];
        $posts = CMSHelper::getQueryBuilder($args)
            ->with(
                [
                    'terms' => function ($term) {
                        return $term->where('slug', '<>', 'novosti');
                    },
                    'tags',
                ]
            )->paginate(1);

        $cityRootTag = BRTag::where([['type', 'tag'], ['slug', 'gorod']])->first();
        $cityTags = BRTag::whereDescendantOf($cityRootTag)->get();

        $newsRootTerm = BRTerm::where([['type', 'category'], ['slug', 'novosti']])->first();
        $newsTerms = BRTerm::whereDescendantOf($newsRootTerm)->get();

        return view('v1.pages.news.list.list')->with(compact('posts', 'cityTags', 'newsTerms', 'request'));
    }

    public function showSingleNews($id){
        $post = BRPost::findOrFail($id);//todo finish
        dd($post);
        return View::make('v1.')->with(compact('post'));
    }

    public function filterAction(Request $request) {
        $args = [
            'term' => ['novosti'],
            'type' => 'post',
        ];

        $posts = CMSHelper::getQueryBuilder($args)
            ->when($request->get('categories'), function ($query) use ($request){
                $query->whereHas('terms', function ($terms) use ($request) {
                    return $terms->whereIn('id', $request->get('categories'));
                });
            })
            ->when($request->get('cities'), function ($query) use ($request){
                $query->whereHas('tags', function ($tags) use ($request) {
                    return $tags->whereIn('id', $request->get('cities'));
                });
            })
            ->when($request->get('daterange'), function ($query) use ($request){
                $dateRange = explode(' - ', $request->get('daterange'));
                $dateFrom = Carbon::createFromFormat('d.m.Y', $dateRange[0])->toDateTimeString();
                $dateTo = Carbon::createFromFormat('d.m.Y', $dateRange[1])->toDateTimeString();

                return $query->whereBetween('published_at',array($dateFrom, $dateTo));
            })
            ->with(
                [
                    'terms' => function ($term) {
                        return $term->where('slug', '<>', 'novosti');
                    },
                    'tags',
                ]
            )->paginate(1);

        $cityRootTag = BRTag::where([['type', 'tag'], ['slug', 'gorod']])->first();
        $cityTags = BRTag::whereDescendantOf($cityRootTag)->get();

        $newsRootTerm = BRTerm::where([['type', 'category'], ['slug', 'novosti']])->first();
        $newsTerms = BRTerm::whereDescendantOf($newsRootTerm)->get();

        $posts->setPath('news-list');

        return View::make('v1.partials.news-page.news')->with(compact('posts', 'cityTags', 'newsTerms', 'request'))->render();
    }
}
