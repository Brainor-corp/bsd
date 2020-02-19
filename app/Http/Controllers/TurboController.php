<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use sokolnikov911\YandexTurboPages\Channel;
use sokolnikov911\YandexTurboPages\Counter;
use sokolnikov911\YandexTurboPages\Feed;
use sokolnikov911\YandexTurboPages\Item;
use sokolnikov911\YandexTurboPages\RelatedItem;
use sokolnikov911\YandexTurboPages\RelatedItemsList;
use Zeus\Admin\Cms\Helpers\CMSHelper;

class TurboController extends Controller
{
    public function rss()
    {
        // creates Feed with all needed namespaces
        $feed = new Feed();

        // creates Channel with description and one ad from Yandex Ad Network
        $channel = new Channel();
        $channel
            ->title('Балтийская служба доставки')
            ->link(url('/'))
            ->description('RSS лента сайта "Балтийская служба доставки"')
            ->language('ru')
//            ->adNetwork(Channel::AD_TYPE_YANDEX, 'RA-123456-7', 'first_ad_place')
            ->appendTo($feed);

        // adds Yandex Metrika to feed
//        $yandexCounter = new Counter(Counter::TYPE_YANDEX, 12345678);
//        $yandexCounter->appendTo($channel);

        $cmsPages = CMSHelper::getQueryBuilder(['type' => 'page'])->whereNotNull('content')->get();
        $news = CMSHelper::getQueryBuilder(['type' => 'news'])->whereNotNull('content')->get();

        $items = $cmsPages->merge($news);
        $items = $items->sortByDesc('published_at')->take(1000);

        foreach($items as $item) {
            // creates first page of feed with link and enabled turbo, description and other content, and appends this page to channel
            $itemModel = new Item();
            $itemModel
                ->title($item->title)
                ->link(url($item->url))
                ->turboContent($item->content)
                ->pubDate(strtotime($item->published_at))
                ->appendTo($channel);
        }

        return response($feed)->header('Content-Type', 'text/xml');
    }
}
