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