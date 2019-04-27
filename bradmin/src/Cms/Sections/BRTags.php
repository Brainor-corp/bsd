<?php

namespace Bradmin\Cms\Sections;

use Bradmin\Cms\Models\BRTag;
use Bradmin\Section;
use Bradmin\SectionBuilder\Display\BaseDisplay\Display;
use Bradmin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Bradmin\SectionBuilder\Form\BaseForm\Form;
use Bradmin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Bradmin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;
use Illuminate\Http\Request;

class BRTags extends Section
{
    protected $title = 'Метки';
    protected $model = 'Bradmin\Cms\Models\BRTag';

    public static function onDisplay(){
        $pluginsFields = app()['PluginsData']['CmsData']['Posts']['DisplayField'] ?? [];
        $brFields = [
            '0.01' => Column::text('id', '#'),
            '0.02' => Column::link('title', 'Название'),
            '0.03' => Column::text('slug', 'Ярлык'),
            '0.04' => Column::text('description', 'Описание')
        ];

        $mergedFields = array_merge($pluginsFields, $brFields);
        ksort($mergedFields);

        $display = Display::table($mergedFields)->setPagination(10);

        return $display->setScopes(['tags']);
    }

    public static function onCreate()
    {
        return self::onEdit(null);
    }

    public static function onEdit($id)
    {
        $pluginsFields = app()['PluginsData']['CmsData']['Tags']['EditField'] ?? [];

        $tags_tree = BRTag::where('type', 'tag')->get()->toTree()->toArray();
        $cur_tag = $id ? BRTag::with('ancestors')->where('id', $id)->first()->toArray() : null;
        $tagsTreeView = view('bradmin::cms.partials.tagsTree')->with(compact('tags_tree', 'cur_tag'));

        $brFields = [
            '0.01' => FormField::input('title', 'Название')->setRequired(true),
            '0.02' => FormField::input('slug', 'Ярлык (необязательно)'),
            '0.03' => FormField::textarea('description', 'Описание'),
            '0.04' => FormField::custom($tagsTreeView),
            '99.99' => FormField::hidden('type')->setValue("tag"),
        ];

        $mergedFields = array_merge($pluginsFields, $brFields);

        ksort($mergedFields);

        $form = Form::panel([
            FormColumn::column($mergedFields),
        ]);

        return $form;
    }

    public function afterSave(Request $request, $model = null)
    {
        if($request->has('parent_id')) {
            $parent = BRTag::where('id', $request->parent_id)->first();
            $parent->appendNode($model);
        } else {
            $model->saveAsRoot();
        }
    }
}