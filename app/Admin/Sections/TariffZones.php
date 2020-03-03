<?php

namespace App\Admin\Sections;

use App\ForwardThreshold;
use App\MaxPackageDimension;
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


class TariffZones extends Section
{
    protected $title = 'Тарифные зоны';
    protected $model = 'App\\Type';

    protected $checkAccess = true;

    public static function onDisplay(Request $request){
        $display = Display::table([
                Column::link('name', 'Название'),
            ])
            ->setScopes(['TariffZones'])
            ->setFilter([
                FilterType::text('name', 'Название'),
            ])
            ->setPagination(25);

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
                FormField::hidden('class')->setValue('tariff_zones'),
                FormField::input('name', 'Наименование')
                    ->setRequired(true),
            ])
        ]);

        return $form;
    }
}
