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
    private $title = 'Балтийская служба доставки',
            $description = 'RSS лента сайта "Балтийская служба доставки"';

    public function rssPages()
    {
        // creates Feed with all needed namespaces
        $feed = new Feed();

        // creates Channel with description and one ad from Yandex Ad Network
        $channel = new Channel();
        $channel
            ->title($this->title)
            ->link(url('/'))
            ->description($this->description)
            ->language('ru')
//            ->adNetwork(Channel::AD_TYPE_YANDEX, 'RA-123456-7', 'first_ad_place')
            ->appendTo($feed);

        // adds Yandex Metrika to feed
//        $yandexCounter = new Counter(Counter::TYPE_YANDEX, 12345678);
//        $yandexCounter->appendTo($channel);

        $items = CMSHelper::getQueryBuilder(['type' => 'page'])
            ->whereNotNull('content')
            ->orderBy('published_at', 'desc')
            ->limit(1000)
            ->get();

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

    public function rssNews()
    {
        // creates Feed with all needed namespaces
        $feed = new Feed();

        // creates Channel with description and one ad from Yandex Ad Network
        $channel = new Channel();
        $channel
            ->title($this->title)
            ->link(url('/'))
            ->description($this->description)
            ->language('ru')
//            ->adNetwork(Channel::AD_TYPE_YANDEX, 'RA-123456-7', 'first_ad_place')
            ->appendTo($feed);

        // adds Yandex Metrika to feed
//        $yandexCounter = new Counter(Counter::TYPE_YANDEX, 12345678);
//        $yandexCounter->appendTo($channel);

        $items = CMSHelper::getQueryBuilder(['type' => 'news'])
            ->whereNotNull('content')
            ->orderBy('published_at', 'desc')
            ->limit(1000)
            ->get();

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
