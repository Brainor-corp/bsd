<?php

namespace App\Admin\Sections;

use App\City;
use App\Oversize;
use App\Region;
use App\Route;
use App\RouteTariff;
use App\Threshold;
use App\Type;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Display\Table\DisplayTable;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
//use Illuminate\Support\Facades\Request;
use Zeus\Admin\SectionBuilder\Meta\Meta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;


class Comments extends Section {
    protected $title = 'Отзывы';

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('user_id', 'id пользователя'),
            Column::text('email', 'Email'),
            Column::text('fio', 'ФИО'),
            Column::text('rating', 'Рейтинг'),
            Column::text('comprehensible_visible', 'Отображать'),
            Column::text('comprehensible_moderate', 'На модерации'),
        ])->setPagination(10);

        return $display;
    }

    public function isCreatable() {
        return false;
    }

    public static function onEdit($id) {

        $form = Form::panel([
            FormColumn::column([
                FormField::input('user_id', 'id пользователя')->setReadonly(true)->setRequired(true),
                FormField::input('email', 'email пользователя')->setReadonly(true)->setRequired(true),
                FormField::input('fio', 'ФИО пользователя')->setReadonly(true)->setRequired(true),
                FormField::input('ip', 'ip пользователя')->setReadonly(true)->setRequired(true),
                FormField::textarea('text', 'Текст отзыва')->setReadonly(true),
                FormField::input('rating', 'Рейтинг отзыва')->setReadonly(true)->setRequired(true),
                FormField::select('visible', 'Отображать отзыв')
                    ->setRequired(true)
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::select('moderate', 'На модерации')
                    ->setRequired(true)
                    ->setOptions([0=>'Нет', 1=>'Да']),
            ])
        ]);

        return $form;
    }
}