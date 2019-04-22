<?php

namespace Bradmin\Navigation;


class NavigationDefault
{
    public static function getNavigationList()
    {
        $navigation = [
            [
                'url' => '/bradmin/Types',
                'icon' => 'fas fa-address-book',
                'text' => 'Типы'
            ],
            [
                'url' => '/bradmin/Cities',
                'icon' => 'fas fa-address-book',
                'text' => 'Города'
            ],
            [
                'url' => '/bradmin/Points',
                'icon' => 'fas fa-address-book',
                'text' => 'Точки'
            ],
            [
                'url' => '/bradmin/Regions',
                'icon' => 'fas fa-address-book',
                'text' => 'Регионы'
            ],
            [
                'url' => '/bradmin/Routes',
                'icon' => 'fas fa-address-book',
                'text' => 'Маршруты'
            ],
            [
                'url' => '/bradmin/RouteTariffs',
                'icon' => 'fas fa-address-book',
                'text' => 'Тарифы маршрутов'
            ],
            [
                'url' => '/bradmin/Terminals',
                'icon' => 'fas fa-address-book',
                'text' => 'Терминалы'
            ],
//            [
//                'url' => '/bradmin/Orders',
//                'icon' => 'fas fa-address-book',
//                'text' => 'Заказы'
//            ],
            [
                'url' => '/bradmin/Oversizes',
                'icon' => 'fas fa-address-book',
                'text' => 'Перегруз'
            ],
            [
                'url' => '/bradmin/ForwardThresholds',
                'icon' => 'fas fa-address-book',
                'text' => 'Предельные пороги'
            ],
            [
                'url' => '/bradmin/InsideForwarding',
                'icon' => 'fas fa-address-book',
                'text' => 'Внутренние пересылки'
            ],
            [
                'url' => '/bradmin/Users',
                'icon' => 'fas fa-address-book',
                'text' => 'Пользователи'
            ],
            [
                'url' => '/bradmin/Services',
                'icon' => 'fas fa-address-book',
                'text' => 'Услуги'
            ],

        ];

        return $navigation;
    }
}