<?php

namespace App\Admin\Sections;

use App\City;
use App\ReviewFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Zeus\Admin\Section;
use Zeus\Admin\SectionBuilder\Display\BaseDisplay\Display;
use Zeus\Admin\SectionBuilder\Display\Table\Columns\BaseColumn\Column;
use Zeus\Admin\SectionBuilder\Form\BaseForm\Form;
use Zeus\Admin\SectionBuilder\Form\Panel\Columns\BaseColumn\FormColumn;
use Zeus\Admin\SectionBuilder\Form\Panel\Fields\BaseField\FormField;

//use Illuminate\Support\Facades\Request;


class Reviews extends Section {
    protected $title = 'Отзывы';

    protected $checkAccess = true;

    public static function onDisplay(Request $request) {
        $display = Display::table([
            Column::link('id', '#'),
            Column::text('author', 'ФИО или организация'),
            Column::text('email', 'Email'),
            Column::text('phone', 'Телефон'),
            Column::text('city.name', 'Город'),
            Column::text('comprehensible_moderate', 'Прошел модерацию?'),
        ])->setPagination(10);

        return $display;
    }

    public static function onCreate() {
        return self::onEdit(null);
    }

    public static function onEdit($id) {
        $file = ReviewFile::where('review_id', $id)->first();
        $form = Form::panel([
            FormColumn::column([
                FormField::input('author', 'ФИО или организация'),
                FormField::input('email', 'Email'),
                FormField::input('phone', 'Телефон')->setRequired(true),
                FormField::textarea('text', 'Текст отзыва'),
                FormField::bselect('city_id', 'Город')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setModelForOptions(City::class)
                    ->setDisplay('name')
                    ->setRequired(true),
                FormField::bselect('moderate', 'Прошел модерацию?')
                    ->setDataAttributes([
                        'data-live-search="true"'
                    ])
                    ->setOptions([0 => 'Нет', 1 => 'Да'])
                    ->setRequired(true),
                FormField::custom(View::make('admin.reviews-section.reviews-file-field')
                    ->with(compact('file'))),
            ])
        ]);

//        $form->setAttributes([
//            'enctype=multipart/form-data'
//        ]);

        return $form;
    }

    public function afterSave(Request $request, $model = null)
    {
        $uploadFile = $request->file('review-file');
        if(!isset($uploadFile)) {
            return;
        }

        $modelFile = $model->file;
        if(isset($modelFile) && file_exists($modelFile->path)) {
            unlink($modelFile->path);
        }

        $model->file()->delete();

        $file = new ReviewFile();
        $storagePath = Storage::disk('available_public')->put('files/review-files', $uploadFile);

        $file->name = $uploadFile->getClientOriginalName();
        $file->mime = $uploadFile->getMimeType();
        $file->extension = $uploadFile->getClientOriginalExtension();
        $file->url = url($storagePath);
        $file->path = Storage::disk('available_public')->path($storagePath);
        $file->base_url = $storagePath;
        $file->size = $uploadFile->getSize();

        $model->file()->save($file);
    }
}