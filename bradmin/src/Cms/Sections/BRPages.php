<?php

namespace Bradmin\Cms\Sections;

use Bradmin\Cms\Models\BRPost;
use Bradmin\Cms\Models\BRTag;
use Bradmin\Cms\Models\BRTerm;
use Bradmin\Cms\Helpers\TemplatesHelper;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Filter\Types\BaseType\FilterType;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BRPages extends Section
{
    protected $title = 'Страницы';
    protected $model = 'Bradmin\Cms\Models\BRPost';

    public static function onDisplay(){
        $pluginsFields = app()['PluginsData']['CmsData']['Pages']['DisplayField'] ?? [];
        $brFields = [
            '0.01' => Column::text('id', '#')->setSortable(true),
            '0.02' => Column::link('title', 'Заголовок')->setSortable(true),
            '0.03' => Column::text('description', 'Краткое описание'),
            '0.04' => Column::text('tags.title', 'Метки'),
            '0.05' => Column::text('categories.title', 'Рубрики'),
            '0.06' => Column::text('status', 'Статус')->setSortable(true),
            '0.07' => Column::text('created_at', 'Дата создания')->setSortable(true),
            '0.08' => Column::text('published_at', 'Дата публикации')->setSortable(true),
        ];

        $mergedFields = array_merge($pluginsFields, $brFields);
        ksort($mergedFields);

        $display = Display::table($mergedFields)->setPagination(10);
        
        $filter = [
          FilterType::text('id', '#'),
          FilterType::text('title', 'Заголовок'),
          null,
          FilterType::select('tags.title')->setOptions(
              BRTerm::where('type', 'tag')->pluck('title', 'id')->toArray()
          ),
        FilterType::select('categories.title')->setOptions(
            BRTerm::where('type', 'category')->pluck('title', 'id')->toArray()
        ),
          null,
          null,
          null,
        ];

        $display->setFilter($filter);

        return $display->setScopes(['pages']);
    }

    public static function onCreate()
    {
        return self::onEdit(null);
    }

    public static function onEdit($id)
    {
        $pluginsFieldsLeft = app()['PluginsData']['CmsData']['Pages']['EditField']['Left'] ?? [];
        $pluginsFieldsRight = app()['PluginsData']['CmsData']['Pages']['EditField']['Right'] ?? [];

        $templates = TemplatesHelper::getTemplates('page');
        $pages_tree = BRPost::where('type', 'page')->orderBy('title')->get()->toTree()->toArray();
        $cur_page = $id ? BRPost::with('ancestors')->where('id', $id)->first()->toArray() : null;
        $pagesTreeView = view('bradmin::cms.partials.pagesTree')->with(compact('pages_tree', 'cur_page'));

        $brFieldsLeft = [
            '0.01' => FormField::input('title', 'Заголовок')->setRequired(true),
            '0.02' => FormField::textarea('description', 'Краткое описание')->setRows(3),
            '0.03' => FormField::input('slug', 'Слаг (необязательно)'),
            '0.04' => FormField::input('url', 'Ссылка ("." для автогенерации)')
                ->setRequired(true)
                ->setValue('.'),
            '0.05' => FormField::multiselect('tags', 'Метки')
                ->setModelForOptions(BRTag::class)
                ->setQueryFunctionForModel(
                    function ($query) {
                        return $query->tags();
                    }
                )
                ->setDisplay('title'),
            '0.06' => FormField::multiselect('categories', 'Рубрики')
                ->setModelForOptions(BRTerm::class)
                ->setQueryFunctionForModel(
                    function ($query) {
                        return $query->categories();
                    }
                )
                ->setDisplay('title'),
            '0.07' => FormField::custom(view('bradmin::SectionBuilder.Form.Fields.InsertMedia.insertMedia')->with('id','input_content')),
            '0.08' => FormField::wysiwyg('content', 'Содержимое'),
        ];
        $brFieldsRight = [
            '0.01' => FormField::select('status', 'Статус')
                ->setOptions([
                    'draft' => 'Черновик',
                    'published' => 'Опубликовано'
                ])
                ->setDefaultSelected('published')
                ->setRequired(true),
            '0.02' => FormField::select('template', 'Шаблон')
                ->setOptions($templates),
            '0.03' => FormField::custom($pagesTreeView),
            '0.04' => FormField::select('comment_on', 'Комментарии')
                ->setOptions([
                    0 => 'Запрещены',
                    1 => 'Разрешены'
                ])
                ->setDefaultSelected(0)
                ->setRequired(true),
            '0.05' => FormField::datepicker('published_at', 'Дата публикации')
                ->setLanguage('ru')
                ->setFormat('yyyy-mm-dd hh:ii:ss')
                ->setClearBtn(true)
                ->setTodayBtn(true)
                ->setValue(Carbon::now())
                ->setRequired(true),
            '0.06' => FormField::custom(view('bradmin::cms.partials.thumb')->with(compact('cur_page'))),
            '99.98' => FormField::hidden('thumb'),
            '99.99' => FormField::hidden('type')->setValue("page"),
        ];

        $mergedFieldsLeft = array_merge($pluginsFieldsLeft, $brFieldsLeft);
        $mergedFieldsRight = array_merge($pluginsFieldsRight, $brFieldsRight);

        ksort($mergedFieldsLeft);
        ksort($mergedFieldsRight);

        $form = Form::panel([
            FormColumn::column($mergedFieldsLeft, 'col-md-8 col-12'),
            FormColumn::column($mergedFieldsRight, 'col-md-4 col-12'),
        ]);

        return $form;
    }

    public function afterSave(Request $request, $model = null)
    {
        $terms = [];
        $terms = array_merge($request->tags ?? [], $terms);
        $terms = array_merge($request->categories ?? [], $terms);
        $model->terms()->detach();
        $model->terms()->attach($terms);

        if($request->url == '.')
        {
            $model->url = $model->default_url;
            $model->save();
        }

        if($request->has('parent_id')) {
            $parent = BRPost::where('id', $request->parent_id)->first();
            $parent->appendNode($model);
        } else {
            $model->saveAsRoot();
        }
    }
}