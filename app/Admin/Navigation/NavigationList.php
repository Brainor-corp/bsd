<?php

namespace App\Admin\Navigation;


class NavigationList
{
    public static function getNavigationList()
    {
        $navigation = [
            [
                'url' => '/'.config('zeusAdmin.admin_url').'/us',
                'icon' => 'fas fa-users',
                'text' => 'Пользователи',
                'noDirect' => true,
                'nodes' => [
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Users',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Список'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Roles',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Роли'
                    ],
                ]
            ],
            [
                'url' => '/'.config('zeusAdmin.admin_url').'/db',
                'icon' => 'fas fa-users',
                'text' => 'Данные таблиц',
                'noDirect' => true,
                'nodes' => [
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Promotions',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Акции'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Types',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Типы'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Cities',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Города'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Points',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Пункты'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Regions',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Регионы'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Routes',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Маршруты'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/RouteTariffs',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Тарифы маршрутов'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Terminals',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Терминалы'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Orders',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Заказы'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Oversizes',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Перегруз'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/ForwardThresholds',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Предельные пороги'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/InsideForwarding',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Внутренние пересылки'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/OutsideForwarding',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Внешние пересылки'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/PerKmTariffs',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Покилометровые тарифы'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/OversizeMarkups',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Множитель перегруза'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Thresholds',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Пределы'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Reviews',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Отзывы'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Supports',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Обращения'
                    ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/ContactEmails',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Имейлы'
                    ],
//            [
//                'url' => '/' . config('zeusAdmin.admin_url') . '/Comments',
//                'icon' => 'fas fa-address-book',
//                'text' => 'Отзывы'
//            ],
//            [
//                'url' => '/' . config('zeusAdmin.admin_url') . '/Contacts',
//                'icon' => 'fas fa-address-book',
//                'text' => 'Контакты'
//            ],
//            [
//                'url' => '/' . config('zeusAdmin.admin_url') . '/Notes',
//                'icon' => 'fas fa-address-book',
//                'text' => 'Заметки'
//            ],
                    [
                        'url' => '/' . config('zeusAdmin.admin_url') . '/Services',
                        'icon' => 'fas fa-address-book',
                        'text' => 'Услуги'
                    ],

                ]
            ],
        ];

        return $navigation;
    }
}