<?php

namespace App\Console\Commands;

use App\City;
use App\CmsBoosterPost;
use App\LandingPage;
use App\Promotion;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Zeus\Admin\Cms\Helpers\CMSHelper;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sitemap generation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sitemap = Sitemap::create();

        // Статичные страницы
        $staticLinks = [
            route('index'),                     // Главная
            route('terminals-addresses-show'),  // Адреса терминала
            route('promotion-list-show'),       // Акции
            route('news-list-show'),            // Новости
            route('reviews'),                   // Отзывы
            route('pricesPage'),                // Прайс-лист
            route('calculator-show'),           // Калькулятор
            route('shipment-search'),           // Поиск груза
        ];
        foreach($staticLinks as $staticLink) {
            $sitemap->add($staticLink);
        }

        // Контакты терминалов
        $cities = City::all();
        foreach($cities as $city) {
            $sitemap->add(route('terminals-addresses-show', ['city' => $city->slug]));
        }

        // Акции
        $promotions = Promotion::all();
        foreach($promotions as $promotion) {
            $sitemap->add(route('promotion-single-show', ['slug' => $promotion->slug]));
        }

        // Новости
        $news = CmsBoosterPost::where([['type', 'news'], ['Status', 'published']])->get();
        foreach($news as $newsItem) {
            $sitemap->add(route('news-single-show', ['slug' => $newsItem->slug]));
        }

        // Посадочные
        $landingPages = LandingPage::all();
        foreach($landingPages as $landingPage) {
            $sitemap->add(route('landing-index', ['url' => $landingPage->url]));
        }

        // CMS
        $args = ['type' => 'page'];
        $cmsPages = CMSHelper::getQueryBuilder($args)->get();
        foreach($cmsPages as $cmsPage) {
            $sitemap->add($cmsPage->url);
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
