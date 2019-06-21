<?php

namespace App\Http\Controllers;

use App\City;
use Bradmin\Cms\Helpers\CMSHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeus\Admin\Cms\Models\ZeusAdminTag;
use Zeus\Admin\Cms\Models\ZeusAdminTerm;

class NewsController extends Controller {
    public function showList(Request $request) {
        if(empty($request->get('cities'))) {
            $currentCityName = null;

            if(Session::has('current_city')) {
                $sessionCity = Session::get('current_city');
                if(isset($sessionCity['name'])) {
                    $currentCityName = $sessionCity['name'];
                }
            }

            if(!isset($currentCityName)) {
                $currentCityName = City::where('slug', 'sankt-peterburg')->firstOrFail()->name;
            }

            $cityTag = ZeusAdminTag::where('title', $currentCityName)->first();
            if(isset($cityTag)) {
                $request->merge(['cities' => [$cityTag->id]]);
            }
        }

        $args = [
            'category' => ['novosti'],
            'type' => 'post',
        ];
        $posts = CMSHelper::getQueryBuilder($args)
            ->whereStatus('published')
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
            )->paginate(10);

        $cityRootTag = ZeusAdminTag::where([['type', 'tag'], ['slug', 'gorod']])->first();
        $cityTags = ZeusAdminTag::whereDescendantOf($cityRootTag)->get();

        $newsRootTerm = ZeusAdminTerm::where([['type', 'category'], ['slug', 'novosti']])->first();
        $newsTerms = ZeusAdminTerm::whereDescendantOf($newsRootTerm)->get();

        return view('v1.pages.news.list.list')->with(compact('posts', 'cityTags', 'newsTerms', 'request'));
    }

    public function showSingleNews($slug){
        $args = [
            'category' => ['novosti'],
            'type' => 'post',
            'slug' => $slug,
        ];
        $post = CMSHelper::getQueryBuilder($args)
            ->whereStatus('published')
            ->with(
                [
                    'terms' => function ($term) {
                        return $term->where('slug', '<>', 'novosti');
                    },
                    'tags',
                ]
            )
            ->firstOrFail();

        return View::make('v1.pages.news.single.single-news')->with(compact('post'));
    }

    public function filterAction(Request $request) {
        $args = [
            'category' => ['novosti'],
            'type' => 'post',
        ];

        $posts = CMSHelper::getQueryBuilder($args)
            ->whereStatus('published')
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
            )->paginate(10);

        $cityRootTag = ZeusAdminTag::where([['type', 'tag'], ['slug', 'gorod']])->first();
        $cityTags = ZeusAdminTag::whereDescendantOf($cityRootTag)->get();

        $newsRootTerm = ZeusAdminTerm::where([['type', 'category'], ['slug', 'novosti']])->first();
        $newsTerms = ZeusAdminTerm::whereDescendantOf($newsRootTerm)->get();

        $posts->setPath('news-list');

        return View::make('v1.partials.news-page.news')->with(compact('posts', 'cityTags', 'newsTerms', 'request'))->render();
    }
}
