<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class LandingPagesHelper
{
    const VIEW_PATH = 'v1/pages/landings/';

    private static function getTemplatesDirectoriesPath()
    {
        return $templatesDirectoriesPath = Config::get('view.paths')[0] . '/' . self::VIEW_PATH;
    }

    public static function getTemplates()
    {
        $templatesDirectoriesPath = self::getTemplatesDirectoriesPath();
        $templates = [];

        foreach(scandir($templatesDirectoriesPath) as $dir) {
            if(in_array($dir, [".", ".."])) {
                continue;
            }

            $templatesDirectory = $templatesDirectoriesPath . $dir;

            if($handle = opendir($templatesDirectory)) {
                while (false !== ($file = readdir($handle))) {
                    if(preg_match("|.php|",$file)){
                        $docComments = array_filter(
                            token_get_all( file_get_contents( $templatesDirectory.'/'.$file ) ), function($entry) {
                            return $entry[0] == T_DOC_COMMENT;
                        }
                        );
                        $fileDocComment = array_shift( $docComments )[1];
                        $commentRows = explode("\n", $fileDocComment);
                        foreach ($commentRows as $commentRow){
                            if(stripos($commentRow,':' )){
                                $commentRowParams = explode(":", $commentRow);
                                $commentParams[trim(ltrim(trim($commentRowParams[0]),'*'))]= trim($commentRowParams[1]);
                            }
                        }
                        if(isset($commentParams)) {
                            if (isset($commentParams['class'])) {
                                if($commentParams['class'] == 'LandingTemplate')
                                {
                                    $templates[$dir] = $commentParams['title'];
                                }
                            }
                        }
                    }
                }
                closedir($handle);
            }
        }

        return $templates;
    }

    public static function generateText($landingPage, $type)
    {
        $route = $landingPage->route;

        $generatorPath = str_replace('/', '.', self::VIEW_PATH)
            . $landingPage->template
            . ".generators.text_$type";

        return view($generatorPath)
            ->with(compact('route'))
            ->render();
    }
}
