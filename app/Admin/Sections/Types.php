<?php

namespace App\Admin\Sections;

use App\Contact;
use App\Equipment;
use App\Role;
use App\Type;
use App\User;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Display\Table\DisplayTable;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
//use Illuminate\Support\Facades\Request;
use Bradmin\SectionBuilder\Meta\Meta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;


class Types extends Section
{
    protected $title = 'Типы';

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('name', 'Название'),
            Column::text('description', 'Описание'),
        ])->setPagination(10);

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

        $type = Type::where('id', $id)->with('equipment')->first();

        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Название')->setRequired(true),
                FormField::input('class', 'Класс')->setRequired(true),
                FormField::textarea('description', 'Описание'),
                FormField::select('optional', 'Выводить при загрузке КП')
                    ->setOptions(['optional'=>'Нет', 'default'=>'Да'])
                    ->setRequired(true),
                FormField::custom(View::make('admin.equipmentAjaxMultiselect')->with(compact('type')))
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
        if(!$request->has('equipment')) {
            $model->equipment()->sync(null);
        }
    }

    public function beforeDelete(Request $request, $id = null)
    {
        $type = Type::where('id', $id)->first();
        $type->equipment()->detach();
        $type->work()->detach();
    }

}