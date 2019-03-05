<?php
/**
 * Created by PhpStorm.
 * User: Артем
 * Date: 10.10.2018
 * Time: 11:54
 */

namespace Bradmin\Cms\Helpers;

use Illuminate\Support\Facades\Config;

class TemplatesHelper
{
    public static function getTemplates($type)
    {
        $templatesDirectory = $type == 'page' ? config('bradmin.cms_pages_templates_path') : config('bradmin.cms_posts_templates_path');
        $templatesDirectory = Config::get('view.paths')[0] . '/' . str_replace('.', '/', $templatesDirectory);
        $templates = [];

        if ($handle = opendir($templatesDirectory)) {
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
                            if($commentParams['class'] == 'BR' . studly_case($type) . 'Template')
                            {
                                $templates[basename($file, ".blade.php")] = $commentParams['title'];
                            }
                        }
                    }
                }
            }
            closedir($handle);
        }

        return $templates;
    }
}