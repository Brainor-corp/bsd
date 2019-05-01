<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\File;

class DocumentHelper {
    public static function generateTBSDocument($templatePath, $documentExtension, Array $parameters, Array $blocks = null){
        $TBS = new \clsTinyButStrong();
        $TBS->Plugin(TBS_INSTALL, \clsOpenTBS::class);

        $TBS->LoadTemplate($templatePath);

        $TBS->SetOption('charset', 'UTF-8');
        $TBS->SetOption('render', TBS_OUTPUT);

        foreach ($parameters as $name => $value){
            $TBS->MergeField($name, $value);
        }

        if(isset($blocks)){
            foreach ($blocks as $blockName => $blockValues){
                $TBS->MergeBlock($blockName, $blockValues);
            }
        }

        $name = md5('docs bsd' . time()) . $documentExtension;
        $path = storage_path('app/public/documents/');

        $tempFile = $path . $name;

        File::makeDirectory($path, $mode = 0777, true, true);

        $TBS->Show(OPENTBS_FILE, $tempFile);
        return $tempFile;
    }
}