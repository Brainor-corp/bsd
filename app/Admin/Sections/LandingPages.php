<?php

namespace App\Admin\Sections;

use App\Http\Helpers\LandingPagesHelper;
use App\LandingPage;
use App\Route;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;


class LandingPages extends Section
{
    protected $title = 'Посадочные страницы';

    protected $checkAccess = true;

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::link('title', 'Заголовок'),
            Column::text('route.name', 'Маршрут'),
        ])
            ->setFilter([
                null,
                FilterType::text('title', 'Заголовок'),
                null,
            ])
            ->setPagination(10);

        return $display;
    }

    public static function onCreate()
    {
        $form = Form::panel([
            FormColumn::column([
                FormField::input('title', 'Заголовок ("." для автогенерации)')
                    ->setValue('.')
                    ->setRequired(true),
                FormField::custom(view('admin.landing-pages.field_url')->render()),
                FormField::bselect('route_id', 'Маршрут')
                    ->setModelForOptions(Route::class)
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setDisplay('dash_name_with_id'),
                FormField::select('template', 'Шаблон страницы')
                    ->setRequired(true)
                    ->setDefaultSelected('default')
                    ->setOptions(LandingPagesHelper::getTemplates()),
                FormField::hidden('text_1')->setValue('.'),
                FormField::hidden('text_2')->setValue('.'),
            ])
        ]);

        return $form;
    }

    public static function onEdit($id)
    {
        $landingPage = LandingPage::find($id);

        $form = Form::panel([
            FormColumn::column([
                FormField::input('title', 'Заголовок ("." для автогенерации)')
                    ->setValue('.')
                    ->setRequired(true),
                FormField::wysiwyg('text_1', 'Текст 1'),
                FormField::wysiwyg('text_2', 'Текст 2'),
            ]),
            FormColumn::column([
                FormField::custom(view('admin.landing-pages.field_url')->with(compact('landingPage'))->render()),
                FormField::bselect('route_id', 'Маршрут')
                    ->setModelForOptions(Route::class)
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setRequired(true)
                    ->setReadonly(true)
                    ->setDisplay('dash_name_with_id'),
                FormField::select('template', 'Шаблон страницы')
                    ->setRequired(true)
                    ->setDefaultSelected('default')
                    ->setOptions(LandingPagesHelper::getTemplates()),
                FormField::input('description', 'SEO описание'),
                FormField::input('key_words', 'SEO ключевые слова'),
                FormField::custom(view('admin.landing-pages.cache-clear')->with(compact('landingPage'))->render()),
            ])
        ]);

        return $form;
    }

    public function beforeSave(Request $request, $model = null)
    {
        if($request->get('title') === '.') {
            $route = Route::find($request->get('route_id'));
            $request->merge(['title' => "Грузоперевозки " . $route->dash_name]);
        }
    }

    public function afterSave(Request $request, $model = null)
    {
        if($request->get('url') === '.') {
            $model->url = $model->slug;
        }

        if($request->get('text_1') === '.') {
            $model->text_1 = LandingPagesHelper::generateText($model, 1);
        }

        if($request->get('text_2') === '.') {
            $model->text_2 = LandingPagesHelper::generateText($model, 2);
        }

        $model->save();
    }
}
