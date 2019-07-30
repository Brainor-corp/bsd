<?php

namespace App\Admin\Sections;

use App\Type;
use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Zeus\Admin\SectionBuilder\Meta\Meta;

//use Illuminate\Support\Facades\Request;


class Types extends Section
{
    protected $title = 'Типы';

    protected $checkAccess = true;

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::link('id', '#'),
            Column::text('name', 'Название'),
            Column::text('class', 'Класс'),
            Column::text('description', 'Описание'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название'),
                FilterType::text('class', 'Класс'),
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
        $meta = new Meta;
        $meta->setScripts([
            'body' => [
                asset('packages/select2/js/select2.min.js'),
                asset('js/admin.js')
            ]
        ])->setStyles([
            'select2' => asset('packages/select2/css/select2.min.css')
        ]);

        $type = Type::where('id', $id)->first();

        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Название')->setRequired(true),
                FormField::input('class', 'Класс')->setRequired(true),
                FormField::textarea('description', 'Описание'),
                FormField::input('option', 'Произвольное значение')
            ])
        ])->setMeta($meta);

        return $form;
    }

//    public function afterSave(Request $request, $model = null)
//    {
//        $type = Type::where('id', $model->id)->first();
//        $type->equipment()->sync($request->equipment);
//    }

    public function beforeSave(Request $request, $model = null)
    {
//        if(!$request->has('equipment')) {
//            $model->equipment()->sync(null);
//        }
    }

    public function beforeDelete(Request $request, $id = null)
    {
//        $type = Type::where('id', $id)->first();
//        $type->equipment()->detach();
//        $type->work()->detach();
    }

}