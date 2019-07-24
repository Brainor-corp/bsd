<?php

namespace App\Http\Controllers;

use App\City;
use App\CmsBoosterPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeus\Admin\Cms\Models\ZeusAdminTerm;

class NewsController extends Controller {
    public function showList(Request $request) {
        if(empty($request->get('cities'))) {
            $currentCity = null;

            if(Session::has('current_city')) {
                $sessionCity = Session::get('current_city');
                if(isset($sessionCity['id'])) {
                    $currentCity = City::where('id', $sessionCity['id'])
                        ->with('closestTerminal')
                        ->first();
                }
            }

            if(!isset($currentCity->closestTerminal)) {
                $currentCity = City::where('slug', 'sankt-peterburg')
                    ->with('closestTerminal')
                    ->firstOrFail();
            }

            $request->merge(['cities' => [$currentCity->id]]);
        }

        $posts = CmsBoosterPost::whereStatus('published')
            ->when(
                $request->get('categories'),
                function ($query) use ($request) {
                    $query->whereHas('terms', function ($terms) use ($request) {
                        return $terms->whereIn('id', $request->get('categories'));
                    }
                );
            })
            ->when(
                $request->get('cities'),
                function ($query) use ($request){
                    $query->whereHas('terminals', function ($terminalsQ) use ($request) {
                        $terminalsQ->whereHas('city', function ($cityQ) use ($request) {
                            return $cityQ->whereIn('id', $request->get('cities'));
                        });
                    }
                );
            })
            ->when(
                $request->get('daterange'),
                function ($query) use ($request) {
                    $dateRange = explode(' - ', $request->get('daterange'));
                    $dateFrom = Carbon::createFromFormat('d.m.Y', $dateRange[0])->toDateTimeString();
                    $dateTo = Carbon::createFromFormat('d.m.Y', $dateRange[1])->toDateTimeString();

                    return $query->whereBetween('published_at',array($dateFrom, $dateTo));
            })
            ->where('type', 'news')
            ->with(
                [
                    'terms' => function ($term) {
                        return $term->where('slug', '<>', 'novosti');
                    },
                    'tags',
                ]
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $cities = City::all();

        $newsRootTerm = ZeusAdminTerm::where([['type', 'category'], ['slug', 'novosti']])->first();
        $newsTerms = ZeusAdminTerm::whereDescendantOf($newsRootTerm)->get();

        return view('v1.pages.news.list.list')->with(compact(
            'posts',
            'cities',
            'newsTerms',
            'request'
        ));
    }

    public function filterAction(Request $request) {
        $posts = CmsBoosterPost::whereStatus('published')
            ->when(
                $request->get('categories'),
                function ($query) use ($request) {
                    $query->whereHas('terms', function ($terms) use ($request) {
                        return $terms->whereIn('id', $request->get('categories'));
                    }
                    );
                })
            ->when(
                $request->get('cities'),
                function ($query) use ($request){
                    $query->whereHas('terminals', function ($terminalsQ) use ($request) {
                        $terminalsQ->whereHas('city', function ($cityQ) use ($request) {
                            return $cityQ->whereIn('id', $request->get('cities'));
                        });
                    }
                    );
                })
            ->when(
                $request->get('daterange'),
                function ($query) use ($request) {
                    $dateRange = explode(' - ', $request->get('daterange'));
                    $dateFrom = Carbon::createFromFormat('d.m.Y', $dateRange[0])->toDateTimeString();
                    $dateTo = Carbon::createFromFormat('d.m.Y', $dateRange[1])->toDateTimeString();

                    return $query->whereBetween('published_at',array($dateFrom, $dateTo));
                })
            ->where('type', 'news')
            ->with(
                [
                    'terms' => function ($term) {
                        return $term->where('slug', '<>', 'novosti');
                    },
                    'tags',
                ]
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $cities = City::all();

        $newsRootTerm = ZeusAdminTerm::where([['type', 'category'], ['slug', 'novosti']])->first();
        $newsTerms = ZeusAdminTerm::whereDescendantOf($newsRootTerm)->get();

        $posts->setPath('news-list');

        return View::make('v1.partials.news-page.news')->with(compact(
            'posts',
            'cities',
            'newsTerms',
            'request'
        ))->render();
    }

    public function showSingleNews($slug){
        $post = CmsBoosterPost::where([
                ['type', 'news'],
                ['Status', 'published'],
                ['slug', $slug],
            ])
            ->with(
                [
                    'terms' => function ($term) {
                        return $term->where('slug', '<>', 'novosti');
                    },
                    'tags',
                    'terminals' => function ($terminalsQ) {
                        return $terminalsQ->has('city')->with('city');
                    }
                ]
            )
            ->firstOrFail();

        return View::make('v1.pages.news.single.single-news')->with(compact('post'));
    }
}
