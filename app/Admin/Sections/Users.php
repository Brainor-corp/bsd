<?php

namespace App\Admin\Sections;

use App\Post;
use App\Role;
use App\User;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Users extends Section
{
    protected $title = 'Пользователи';

    public static function onDisplay(){
        $display = Display::table([
            Column::text('id', '#'),
            Column::text('name', 'Имя'),
            Column::text('email', 'EMail'),
            Column::text('phone', 'Телефон'),
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate()
    {
        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Имя')->setRequired(true),
                FormField::input('surname', 'Фамилия'),
                FormField::input('patronomic', 'Отчество'),
                FormField::hidden('verified')->setValue(0),
                FormField::input('email', 'EMail')->setRequired(true)
                    ->setType('email'),
                FormField::input('phone', 'Телефон'),
                FormField::input('password', 'Пароль')
                    ->setRequired(true)
                    ->setType('password'),
                FormField::input('repeat_password', 'Повторите пароль')
                    ->setRequired(true)
                    ->setType('password'),
            ]),
        ]);

        return $form;
    }

    public static function onEdit()
    {
        $form = Form::panel([
            FormColumn::column([
                FormField::input('name', 'Имя')->setRequired(true),
                FormField::input('surname', 'Фамилия')->setRequired(true),
                FormField::input('patronomic', 'Отчество')->setRequired(true),
                FormField::input('email', 'EMail')->setRequired(true),
                FormField::input('phone', 'Телефон'),
                FormField::multiselect('roles', 'Роли')
                    ->setModelForOptions(Role::class)
                    ->setDisplay('name'),
            ]),
        ]);

        return $form;
    }

    public function beforeSave(Request $request, $model = null)
    {
        if($request->password !== $request->repeat_password){
            throw  new \Exception("Пароли не совпадают, попытайтесь снова");
        }

        $duplicate = User::where([['email', $request->email],['id', '!=', $request->id]])->first();
        if($duplicate){
            throw  new \Exception("Пользователь с таким адресом электронной почты уже зарегестрирован!");
        }
    }

    public function afterSave(Request $request, $model = null)
    {
        if($request->has('password')){
            $model->password = Hash::make($request->password);
            $model->save();
        }
    }

}