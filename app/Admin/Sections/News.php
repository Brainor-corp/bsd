<?php

namespace App\Admin\Sections;

use App\Terminal;
use Illuminate\Http\Request;
use Zeus\Admin\Cms\Sections\ZeusAdminPosts;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

class News extends ZeusAdminPosts
{
    protected $title = 'Новости';
    protected $model = 'App\\CmsBoosterPost';

    protected $checkAccess = true;

    public static function onEdit(Request $request, $id)
    {
        $form = parent::onEdit($request, $id); // TODO: Change the autogenerated stub

        $leftColumn = $form->getColumns()[0];
        $leftColumnFields = $leftColumn->getFields();
        $newFields = [
            FormField::bselect('terminals', 'Терминалы')
                ->setDataAttributes([
                    'multiple', 'data-live-search="true"'
                ])
                ->setModelForOptions(Terminal::class)
                ->setDisplay('name_with_city')
        ];

        array_splice(
            $leftColumnFields,
            6,
            0,
            $newFields
        );

        $leftColumn->setFields($leftColumnFields);

        $rightColumn = $form->getColumns()[1];
        $rightColumnFields = $rightColumn->getFields();
        $rightColumnFields['99.99'] = FormField::hidden('type')->setValue('news');
        $rightColumn->setFields($rightColumnFields);

        $form->setColumns([
            $leftColumn,
            $rightColumn
        ]);

        return $form;
    }

    public static function onCreate(Request $request = null, $id = null)
    {
        return self::onEdit($request, $id);
    }
}