<?php

namespace Bradmin\Cms\Controllers;

use Bradmin\Cms\Helpers\CMSHelper;
use Bradmin\Cms\Providers\Cms;
use Bradmin\Controllers\BrAdminController;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

use Bradmin\Cms\Models\BRFile;
use Carbon\Carbon;
use Image;
use Bradmin\Section;


class CmsController extends Controller
{
    private $pluginData = [
        'sectionPath' => 'Bradmin\Cms\Sections\\',
        'redirectUrl' => '/bradmin/cms/{sectionName}'
    ];

    public function __construct(\Illuminate\Contracts\Foundation\Application $app)
    {
        $this->app = $app;
    }

    public function showRouteRedirect(Section $section, $sectionName, Request $request)
    {
        $mainController = new BrAdminController;
        return $mainController->getDisplay($section, $sectionName, $this->pluginData,  $request);
    }

    public function createRouteRedirect(Section $section, $sectionName)
    {
        $mainController = new BrAdminController;

        return $mainController->getCreate($section, $sectionName, $this->pluginData);
    }

    public function editRouteRedirect(Section $section, $sectionName, $id)
    {
        $mainController = new BrAdminController;

        return $mainController->getEdit($section, $sectionName, $id, $this->pluginData);
    }

    public function createActionRouteRedirect(Section $section, $sectionName, Request $request)
    {
        $mainController = new BrAdminController;

        return $mainController->createAction($section, $sectionName, $request);
    }

    public function editActionRouteRedirect(Section $section, $sectionName, $id, Request $request)
    {
        $mainController = new BrAdminController;

        return $mainController->editAction($section, $sectionName, $id, $request);
    }

    public function deleteActionRouteRedirect(Section $section, $sectionName, $id, Request $request)
    {
        $mainController = new BrAdminController;

        return $mainController->deleteAction($section, $sectionName, $id, $request);
    }

    public static function showPage($slug)
    {
        $args = [
            'type' => 'page',
            'slug' => $slug,
        ];
        $page = CMSHelper::getQueryBuilder($args);
        $page = $page->first();

        if(!$page)
        {
            abort(404, 'Страница не найдена');
        }

        $templatePath = config('bradmin.cms_pages_templates_path') . '.' . $page->template;
        if(!View::exists($templatePath))
        {
            throw new \Exception('Шаблон ' . $templatePath . ' не найден');
        }

        return [
            'view'=>$templatePath,
            'data'=>compact('page')
        ];
    }

    public static function showPost($slug)
    {
        $args = [
            'type' => 'post',
            'slug' => $slug,
        ];
        $post = CMSHelper::getQueryBuilder($args);
        $post = $post->first();

        if(!$post)
        {
            abort(404, 'Запись не найдена');
        }

        $templatePath = config('bradmin.cms_posts_templates_path') . '.' . $post->template;
        if(!View::exists($templatePath))
        {
            throw new \Exception('Шаблон ' . $templatePath . ' не найден');
        }

        return [
            'view'=>$templatePath,
            'data'=>compact('post')
        ];
    }

    public function fileUpload(Request $request)
    {
        $file = $request->file('file');
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $day = Carbon::now()->day;
        $timestamp = Carbon::now()->format('YmdHis');
        $pathPart='/uploads/'.$year.'/'.$month.'/'.$day;
        $destinationPath = public_path().''.$pathPart;
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $originalFilename = $file->getClientOriginalName();
        $basename = preg_replace('/\.\w+$/', '', $originalFilename);
        $extension = $file->getClientOriginalExtension();
        $filename = $basename;
        if (file_exists(''.$destinationPath.'/'.$filename.'.'.$extension)) {
            $filename = $basename.'-'.$timestamp;
        }
        $resultFileName = str_replace(' ', '_', $filename.'.'.$extension);
        $fileMime = $file->getMimeType();
        $fileSize = $file->getSize();
        $upload_success = $file->move($destinationPath, $resultFileName);
        $newFilePath = $destinationPath.'/'.$resultFileName;
        if ($upload_success) {
            $url = $pathPart.'/'.$resultFileName;
            $base_url = $pathPart.'/'.$filename;
            $newFile = new BRFile;

            $newFile->mime = $fileMime;
            $newFile->url = $url;
            $newFile->base_url = $base_url;
            $newFile->extension = $extension;
            $newFile->path = $destinationPath;
            $newFile->size = $fileSize;

            $newFile->save();
            if(strstr($fileMime, "image/")){
                $thumbPath = $destinationPath.'/'.$filename.'-200x200'.'.'.$extension;
                Image::make($newFilePath)->fit(200)->save($thumbPath);
            }
            return response()->json(
                [
                    'success' => 200,
                ]
            );
        } else {
            return response()->json(
                [
                    'error' => 400,
                ]
            );
        }
    }
}
