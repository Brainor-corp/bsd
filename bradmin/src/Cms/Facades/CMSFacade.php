<?php
/**
 * Created by PhpStorm.
 * User: Alfred
 * Date: 21.08.2017
 * Time: 8:39
 */

namespace Bradmin\Cms\Facades;

use Bradmin\Cms\Helpers\CMSHelper;
use Illuminate\Support\Facades\Facade;

class CMSFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CMSHelper::class;
    }
}
