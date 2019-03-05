<?php

namespace Bradmin\Controllers;

use Bradmin\Helpers\BRHelper;
use Bradmin\SectionBuilder\Display\Custom\DisplayCustom;
use Bradmin\SectionBuilder\Form\FormAction\FormAction;
use Bradmin\SectionBuilder\Meta\Meta;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Image;

use Bradmin\Section;
use Bradmin\Navigation\NavigationManager;
use Bradmin\Cms\Models\BRFile;

class BrAdminController extends Controller
{

    public function getIndex()
    {
        return view('bradmin::spa');
    }

    public function getDashboard()
    {
        return response()->json([
                'html' => View::make('bradmin::dashboard')->render(),
                'meta' => [
                    'title' => 'Главная'
                ]
            ]
        );
    }

    public function getSidebarMenu(\Illuminate\Contracts\Foundation\Application  $app)
    {
        $navigation = NavigationManager::returnNavigation($app);

        return response()->json($navigation);
    }

    public function getDisplay(Section $section, $sectionName, $pluginData = null, Request $request)
    {
        $display = $section->fireDisplay($sectionName, [$request], $pluginData['sectionPath'] ?? null);
        $meta = $display->getMeta();
        $sectionModelSettings = $section->getSectionSettings(studly_case($sectionName), $pluginData['sectionPath'] ?? null);

        $firedSection = $section->getSectionByName($sectionName, $pluginData['sectionPath'] ?? null);
        if($display instanceof DisplayCustom) {
            $results = $display->render($firedSection, $pluginData);
        } else {
            $results = $display->render($sectionModelSettings['model'] ?? config('bradmin.base_models_path') . studly_case(strtolower(str_singular($sectionName))), $firedSection, $pluginData, $request);
        }

        $html = $results['view'];

        $pagination = [];
        if(!isset($results['isCustom'])) {
            $pagination = [
                'total' => $results['data']->total(),
                'per_page' => $results['data']->perPage(),
                'current_page' => $results['data']->currentPage(),
                'last_page' => $results['data']->lastPage(),
                'from' => $results['data']->firstItem(),
                'to' => $results['data']->lastItem()
            ];
        }


        $meta = [
            'title' => $sectionModelSettings['title'],
            'scripts' => $meta->getScripts(),
            'styles' => $meta->getStyles(),
        ];

        return $this->render($html,$pagination,$meta);
    }

    public function getCreate(Section $section, $sectionName, $pluginData = null)
    {
        $firedSection = $section->getSectionByName($sectionName, $pluginData['sectionPath'] ?? null);
        if(isset($firedSection)) {
            if ($firedSection->isCreatable()) {
                $display = $section->fireCreate(studly_case($sectionName), [], $pluginData['sectionPath'] ?? null);
                $meta = $display->getMeta();
                $sectionModelSettings = $section->getSectionSettings(studly_case($sectionName), $pluginData['sectionPath'] ?? null);

                $html = $display->render($sectionModelSettings['model'] ?? config('bradmin.base_models_path') . studly_case(strtolower(str_singular($sectionName))), $sectionName, $firedSection, null, $pluginData);
                $meta = [
                    'title' => $sectionModelSettings['title'] . '| Новая запись',
                    'scripts' => $meta->getScripts(),
                    'styles' => $meta->getStyles(),
                ];

                return $this->render($html, '', $meta);
            }
            else{
                return $this->render("Создание в этой секции невозможно");
            }
        } else
        {
            return $this->render("Секция не найдена");
        }
    }


    public function getEdit(Section $section, $sectionName, $id, $pluginData = null)
    {
        $firedSection = $section->getSectionByName($sectionName, $pluginData['sectionPath'] ?? null);
        if(isset($firedSection)) {
            if ($firedSection->isEditable()) {
                $display = $section->fireEdit(studly_case($sectionName), [$id], $pluginData['sectionPath'] ?? null);
                $meta = $display->getMeta();
                $sectionModelSettings = $section->getSectionSettings(studly_case($sectionName), $pluginData['sectionPath'] ?? null);

                $html = $display->render($sectionModelSettings['model'] ?? config('bradmin.base_models_path') . studly_case(strtolower(str_singular($sectionName))), $sectionName, $firedSection, $id, $pluginData);
                $meta = [
                    'title' => $sectionModelSettings['title'] . '| Редактирование',
                    'scripts' => $meta->getScripts(),
                    'styles' => $meta->getStyles(),
                ];

                return $this->render($html, '', $meta);
            }
            else{
                return $this->render("Редактирование этой секции невозможно.");
            }
        }
    }

    public function createAction(Section $section, $sectionName, Request $request)
    {
        $class = $section->getSectionByName($sectionName, $request->pluginData['sectionPath'] ?? null);
        $redirectUrl = $request->pluginData['deleteUrl'] ?? '/' . config('bradmin.admin_url') . '/' . $sectionName;
        if(!isset($class)) { abort(500); }
        if ($class->isEditable()) {
            $request->offsetUnset('_token');
            $sectionModelSettings = $section->getSectionSettings(studly_case($sectionName), $request->pluginData['sectionPath'] ?? null);
            $modelPath = $sectionModelSettings['model'] ?? config('bradmin.base_models_path') . studly_case(strtolower(str_singular($sectionName)));
            $request->offsetUnset('pluginData');

            $model = new $modelPath;
            $attrFields = Schema::getColumnListing($model->getTable());
//            $relationFields = array_diff_key($request->all(), array_flip($attrFields));

            $class->beforeSave($request, $model);

            $model = $model::create($request->only($attrFields));
            $model = $model->where('id', $model->id)
//                ->when(isset($relationFields), function ($query) use ($relationFields) {
//                    $query->with(array_keys($relationFields));
//                })
                ->first();


            //        FormAction::save($model, $request);
            FormAction::saveBelongsToRelations($model, $request);
            FormAction::saveBelongsToManyRelations($model, $request);
            FormAction::saveHasOneRelations($model, $request);

            $class->afterSave($request, $model);

            //        return redirect()->back();

            return response()->json([
                    'data' => [
                        'code' => 0,
                        'message' => 'Успешно',
                        'class' => 'success'
                    ],
                    'redirect' => [
                        'url' => $redirectUrl
                    ]
                ]
            );
        }
    }

    public function editAction(Section $section, $sectionName, Request $request, $id)
    {
        $class = $section->getSectionByName($sectionName, $request->pluginData['sectionPath'] ?? null);
        $redirectUrl = $request->pluginData['deleteUrl'] ?? '/' . config('bradmin.admin_url') . '/' . $sectionName;
        if(!isset($class)) { abort(500); }
        if ($class->isEditable()) {
            $request->offsetUnset('_token');
            $sectionModelSettings = $section->getSectionSettings(studly_case($sectionName), $request->pluginData['sectionPath'] ?? null);
            $modelPath = $sectionModelSettings['model'] ?? config('bradmin.base_models_path') . studly_case(strtolower(str_singular($sectionName)));
            $request->offsetUnset('pluginData');

            $model = new $modelPath;
            $relationFields = array_keys(BRHelper::getModelRelationships($model));

            $model = $model->where('id', $id)
                ->when(isset($relationFields), function ($query) use ($relationFields) {
                    $query->with($relationFields);
                })
                ->first();

            $class->beforeSave($request, $model);

            FormAction::save($model, $request);
            FormAction::saveBelongsToRelations($model, $request);
            FormAction::saveBelongsToManyRelations($model, $request);
            FormAction::saveHasOneRelations($model, $request);


            $class->afterSave($request, $model);

            //        $modelPath::where('id', $id)->update($request->all());

            return response()->json([
                    'data' => [
                        'code' => 0,
                        'message' => 'Успешно',
                        'class' => 'success'
                    ],
                    'redirect' => [
                        'url' => $redirectUrl
                    ]
                ]
            );
        }
    }

    public function deleteAction(Section $section, $sectionName, $id, Request $request)
    {
        $sectionModelSettings = $section->getSectionSettings($sectionName, $request->pluginData['sectionPath'] ?? null);
        $modelPath = $sectionModelSettings['model'] ?? config('bradmin.base_models_path') . studly_case(strtolower(str_singular($sectionName)));
        $model = new $modelPath;
        $class = $section->getSectionByName($sectionName, $request->pluginData['sectionPath'] ?? null);
        if(!isset($class)) { abort(500); }
        $redirectUrl = $request->pluginData['deleteUrl'] ?? '/'.config('bradmin.admin_url').'/'.$sectionName;
        if($class->isDeletable()){
            $class->beforeDelete($request, $id);
            $model->where('id', $id)->delete();
            return response()->json([
                    'data' => [
                        'code'=>0,
                        'message'=>'Успешно',
                        'class'=>'success'
                    ],
                    'redirect' => [
                        'url' => $redirectUrl
                    ]
                ]
            );
        }
        else{
            return response()->json([
                    'data' => [
                        'code'=>500,
                        'message'=>'Ошибка',
                        'class'=>'error'
                    ],
                    'redirect' => [
                        'url' => $redirectUrl
                    ]
                ]
            );
        }
    }

    public function render($html, $pagination=null, $meta=null)
    {
        return response()->json([
                'html' => View::make('bradmin::content.general')->with(compact('html'))->render(),
                'data' => [
                    'pagination' => $pagination ?? '',
                    ],
                'meta' => $meta ?? ''
            ]
        );
    }

    public function getImage($path){
        return response()->file(base_path() . '/bradmin/images/' . $path);
    }

    public function postEdit()
    {

    }

    public function imageList(Request $request)
    {
        $files = BRFile::
//            when(
//                $request->has('fileType'),
//                function ($query) use ($request) {
//                    return $query->where('mime','ilike','%'.$request->fileType.'%');
//                }
//            )
        limit($request->quantity)
        ->offset($request->requestCount*$request->quantity)
            ->orderBy('created_at','DESC')
            ->get();
        $requestCount = $request->requestCount+1;
        $wrapperId = $request->wrapperId;
        if ($request->requestCount > 0){
            return view('bradmin::SectionBuilder.Form.Fields.InsertMedia.imagesListElements')->with(compact('files','requestCount','wrapperId'));
        }
        else{
            return view('bradmin::SectionBuilder.Form.Fields.InsertMedia.imagesList')->with(compact('files','requestCount','wrapperId'));
        }
    }

}
