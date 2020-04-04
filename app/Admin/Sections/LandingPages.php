<?php

namespace App\Admin\Sections;

use App\Http\Helpers\LandingPagesHelper;
use App\Http\Helpers\TextHelper;
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

    protected $model = 'App\LandingPage';

    protected $checkAccess = true;

    public static function onDisplay(Request $request){
        $display = Display::table([
            Column::text('id', '#'),
            Column::link('strip_tags_title', 'Заголовок'),
            Column::text('route.name', 'Маршрут'),
        ])
            ->setFilter([
                null,
                FilterType::text('title', 'Заголовок'),
                FilterType::bselect('route_id')
                    ->setModelForOptions(Route::class)
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setDisplay("dash_name_with_id")
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
                FormField::hidden('seo_title')->setValue('.'),
                FormField::hidden('description')->setValue('.'),
                FormField::hidden('key_words')->setValue('.'),
            ])
        ]);

        return $form;
    }

    public static function onEdit($id)
    {
        $landingPage = LandingPage::find($id);

        $form = Form::panel([
            FormColumn::column([
                FormField::wysiwyg('title', 'Заголовок ("." в режиме "Источник" для автогенерации)')->setRequired(true),
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
                FormField::input('seo_title', 'SEO заголовок'),
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
            $request->merge(['title' => "<h1>Грузоперевозки $route->dash_name</h1>"]);
        }
    }

    public function afterSave(Request $request, $model = null)
    {
        $route = $model->route;

        if($request->get('url') === '.') {
            $model->url = $model->slug;
        }

        if($request->get('text_1') === '.') {
            $model->text_1 = LandingPagesHelper::generateText($model, 1);
        }

        if($request->get('text_2') === '.') {
            $model->text_2 = LandingPagesHelper::generateText($model, 2);
        }

        if($request->get('seo_title') === '.') {
            $model->seo_title = "Грузоперевозки $route->dash_name";
        }

        if($request->get('key_words') === '.') {
            $model->key_words = "Грузоперевозки $route->dash_name, Доставка документов $route->dash_name, Контейнерные перевозки $route->dash_name";
        }

        if($request->get('description') === '.') {
            $daysTitle = TextHelper::daysTitleByCount($route->delivery_time);
            $model->description = "Балтийская Служба Доставки осуществляет грузоперевозки по маршруту $route->dash_name. От $route->min_cost руб. От $route->delivery_time $daysTitle. Онлайн калькулятор доставки.";
        }

        $model->save();
    }
}
