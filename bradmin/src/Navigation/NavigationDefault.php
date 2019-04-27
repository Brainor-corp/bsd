<?php

namespace Bradmin\Navigation;


class NavigationDefault
{
    public static function getNavigationList()
    {
        $navigation = [
            [
                'url' => '/'.config('bradmin.admin_url').'/db',
                'icon' => 'fas fa-users',
                'text' => 'Данные таблиц',
                'noDirect' => true,
                'nodes' => [
                    [
                        'url' => '/bradmin/Promotions',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Акции'
                    ],
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
                        'text' => 'Пункты'
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
                    [
                        'url' => '/bradmin/Orders',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Заказы'
                    ],
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
                        'url' => '/bradmin/OutsideForwarding',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Внешние пересылки'
                    ],
                    [
                        'url' => '/bradmin/PerKmTariffs',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Покилометровые тарифы'
                    ],
                    [
                        'url' => '/bradmin/OversizeMarkups',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Множитель перегруза'
                    ],
                    [
                        'url' => '/bradmin/Thresholds',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Пределы'
                    ],
                    [
                        'url' => '/bradmin/Reviews',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Отзывы'
                    ],
//            [
//                'url' => '/bradmin/Comments',
//                'icon' => 'fas fa-address-book',
//                'text' => 'Отзывы'
//            ],
//            [
//                'url' => '/bradmin/Contacts',
//                'icon' => 'fas fa-address-book',
//                'text' => 'Контакты'
//            ],
//            [
//                'url' => '/bradmin/Notes',
//                'icon' => 'fas fa-address-book',
//                'text' => 'Заметки'
//            ],
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

                ]
            ],
        ];

        return $navigation;
    }
}