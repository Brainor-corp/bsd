<?php

namespace Bradmin\Navigation;


class NavigationDefault
{
    public static function getNavigationList()
    {
        $navigation = [
            [
                'url' => '/bradmin/Equipments',
                'icon' => 'fas fa-address-book',
                'text' => 'Оборудование'
            ],
            [
                'url' => '/bradmin/OfferGroups',
                'icon' => 'fas fa-address-book',
                'text' => 'Группы КП'
            ],
            [
                'url' => '/bradmin/Types',
                'icon' => 'fas fa-address-book',
                'text' => 'Типы'
            ],
            [
                'url' => '/bradmin/Settings',
                'icon' => 'fas fa-cog',
                'text' => 'Настройки'
            ],
            [
                'url' => '/bradmin/Users',
                'icon' => 'fas fa-address-book',
                'text' => 'Пользователи'
            ],
            [
                'url' => '/bradmin/ExcelUploads',
                'icon' => 'far fa-file-excel',
                'text' => 'Excel загрузка'
            ]
        ];

        return $navigation;
    }
}