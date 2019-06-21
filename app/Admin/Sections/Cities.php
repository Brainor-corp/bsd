<?php

namespace App\Admin\Sections;

use App\ForwardThreshold;
use App\Region;
use App\Terminal;
use App\Type;
use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

//use Illuminate\Support\Facades\Request;


class Cities extends Section
{
    protected $title = 'Города';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Название'),
            Column::text('comprehensive_is_ship', 'Можно доставить'),
            Column::text('comprehensive_is_filial', 'Является филиалом'),
            Column::text('comprehensive_doorstep', 'Доставка до двери'),
            Column::text('tariffZone.name', 'Тарифная зона'),
            Column::text('thresholdGroup.name', 'Группа отправных пунктов'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название'),
                null,
                null,
                null,
                null,
                null,
            ])
            ->setPagination(10);

        return $display;
    }

    public static function onCreate()
    {
        return self::onEdit(null);
    }

    public static function onEdit($id)
    {
        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Наименование')
                    ->setRequired(true),
                FormField::bselect('is_ship', 'Отправка')
                    ->setHelpBlock("<small class='text-muted'>Это город отправления</small>")
                    ->setRequired(1)
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::bselect('is_filial', 'Филиал')
                    ->setHelpBlock("<small class='text-muted'>Это филиал</small>")
                    ->setRequired(1)
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::bselect('message', 'Сообщение')
                    ->setHelpBlock("<small class='text-muted'>Стандартное сообщение, если нет адреса и телефона</small>")
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query) {
                        return $query->where('class', 'system_message');
                    })
                    ->setDisplay('name'),
                FormField::bselect('doorstep', 'До двери')
                    ->setHelpBlock("<small class='text-muted'>В этом городе доставка осуществляется в обязательном режиме \"До двери\"</small>")
                    ->setRequired(1)
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::bselect('tariff_zone_id', 'Тарифная зона')
                    ->setHelpBlock("<small class='text-muted'>Тарифная зона внешней экспедиции</small>")
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'tariff_zones');
                    })
                    ->setDisplay('name'),
                FormField::bselect('threshold_group_id', 'Группа пределов')
                    ->setHelpBlock("<small class='text-muted'>Группа пределов</small>")
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setModelForOptions(Type::class)
                    ->setQueryFunctionForModel(function ($query){
                        return $query->where('class', 'threshold_groups');
                    })
                    ->setDisplay('name'),
                FormField::bselect('is_popular', 'Показывать в популярных городах')
                    ->setHelpBlock("<small class='text-muted'>Популярные города выводятся в окне выбора города в шапке сайта</small>")
                    ->setRequired(1)
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::bselect('closest_terminal_id', 'Ближайший терминал')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setHelpBlock("<small class='text-muted'>Терминал, расположенный к городу ближе прочих. В шапке сайта выводится телефон ближайшего терминала выбранного города.</small>")
                    ->setModelForOptions(Terminal::class)
                    ->setDisplay('name')
                    ->setRequired(1),
                FormField::bselect('update_closest_terminal', 'Обновлять ближайший терминал')
                    ->setHelpBlock("<small class='text-muted'>Указывает, обновлять ли ближайший терминал для текущего города в автоматическом режиме</small>")
                    ->setRequired(1)
                    ->setOptions([0=>'Нет', 1=>'Да']),
                FormField::related('insideForwarding', 'Тарифы', \App\InsideForwarding::class, [
                    FormField::bselect('forward_threshold_id', 'Предел')
                        ->setDataAttributes([
                            'data-live-search="true"'
                        ])
                        ->setRequired(true)
                        ->setModelForOptions(ForwardThreshold::class)
                        ->setDisplay('name'),
                    FormField::input('tariff', 'Тариф')->setType('number')->setRequired(true),
                ]),
                FormField::related('terminals', 'Терминалы', Terminal::class, [
                    FormField::input('name', 'Наименование')->setRequired(true),
                    FormField::input('address', 'Адрес')->setRequired(true),
                    FormField::input('phone', 'Телефон')->setRequired(true),
                    FormField::bselect('region_code', 'Регион')
                        ->setDataAttributes([
                            'data-live-search="true"'
                        ])
                        ->setRequired(true)
                        ->setModelForOptions(Region::class)
                        ->setField('code')
                        ->setDisplay('name'),
                    FormField::input('geo_point', 'Геокоординаты  (x.xxx, y.yyy)')
                        ->setPattern("(\d+\.\d+|\d+),\s(\d+\.\d+|\d+)"),
                ])
            ])
        ]);

        return $form;
    }

    public function beforeDelete(Request $request, $id = null)
    {
        \App\InsideForwarding::where('city_id', $id)->delete();
        Terminal::where('city_id', $id)->delete();
    }
}