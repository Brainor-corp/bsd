<?php

namespace App\Admin\Sections;

use App\Promotion;
use App\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zeus\Admin\Cms\Models\ZeusAdminTerm;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

//use Illuminate\Support\Facades\Request;


class Promotions extends Section
{
    protected $title = 'Акции';

    protected $checkAccess = true;

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::link('title', 'Название'),
            Column::text('amount', 'Скидка (%)'),
            Column::text('start_at', 'Начало акции'),
            Column::text('end_at', 'Окончание акции'),
            Column::text('created_at', 'Дата создания'),
        ])->setPagination(10);

        $user = Auth::user();
        if($user->hasRole('regionalnyy-menedzher')) {
            $display->setScopes([
                'regionalManager'
            ]);
        }

        return $display;
    }

    public static function onCreate()
    {
        return self::onEdit(null);
    }

    public static function onEdit($id)
    {
        $user = Auth::user();
        $userCities = $user->cities;

        if(isset($id) && $user->hasRole('regionalnyy-menedzher')) {
            if(!count($userCities) || !Promotion::where('id', $id)->whereHas('terminals', function ($terminalsQuery) use ($userCities) {
                    return $terminalsQuery->whereHas('city', function ($cityQuery) use ($userCities) {
                        return $cityQuery->whereIn('id', $userCities->pluck('id'));
                    });
                })->exists())
            {
                abort(404);
            }
        }

        $form = Form::panel([
            FormColumn::column([
                FormField::input('title', 'Название')->setRequired(true),
                FormField::textarea('text', 'Текст'),
                FormField::input('amount', 'Скидка (%)')->setType('number'),
                FormField::bselect('terminals', 'Терминалы')
                    ->setDataAttributes([
                        'multiple', 'data-live-search="true"'
                    ])
                    ->setModelForOptions(Terminal::class)
                    ->setQueryFunctionForModel(function ($terminalsQuery) use ($user, $userCities) {
                        if($user->hasRole('regionalnyy-menedzher')) {
                            return $terminalsQuery->whereIn('city_id', count($userCities) ? $userCities->pluck('id') : []);
                        }

                        return $terminalsQuery;
                    })
                    ->setDisplay('name_with_city'),
                FormField::bselect('terms', 'Метки')
                    ->setDataAttributes([
                        'multiple', 'data-live-search="true"'
                    ])
                    ->setModelForOptions(ZeusAdminTerm::class)
                    ->setDisplay('title'),
                FormField::datepicker('start_at', 'Начало акции')
                    ->setLanguage('ru')
                    ->setMinuteStep(10),
                FormField::datepicker('end_at', 'Окончание акции акции')
                    ->setLanguage('ru')
                    ->setMinuteStep(10),
            ])
        ]);

        return $form;
    }


}