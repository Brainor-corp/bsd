<?php

namespace App\Admin\Sections;

use App\Service;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Zeus\Admin\SectionBuilder\Meta\Meta;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Request;


class Services extends Section
{
    protected $title = 'Услуги';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Название'),
            Column::text('slug', 'Ярлык'),
            Column::text('price', 'Цена'),
        ])
            ->setFilter([
                null,
                FilterType::text('name', 'Название'),
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
        $meta = new Meta;
        $meta->setScripts([
            'body' => [
                asset('packages/select2/js/select2.min.js'),
                asset('js/admin.js')
            ]
        ])->setStyles([
            'select2' => asset('packages/select2/css/select2.min.css')
        ]);

        $service = Service::where('id', $id)->first();

        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Название')->setRequired(true),
                FormField::input('slug', 'Ярлык'),
                FormField::textarea('description', 'Описание'),
                FormField::input('price', 'Цена')
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