<?php

namespace Bradmin;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Meta;

class Section
{
    protected $model = null;
    protected $title = null;

    private $class;

    public function __construct(\Illuminate\Contracts\Foundation\Application $app)
    {
        $this->app = $app;
    }

    public function getClassName()
    {
        return __CLASS__;
    }

    public function getSectionSettings($sectionName, $sectionPath = null)
    {
        $section = ($sectionPath ?? config('bradmin.user_path').'\\Sections\\' ) . $sectionName;

        return get_object_vars(new $section($this->app));
    }
    
    public function getSectionByName($sectionName, $sectionPath = null){

        $section =  ($sectionPath ?? config('bradmin.user_path').'\\Sections\\') . $sectionName;
        return new $section($this->app);
    }

    public function fireDisplay($sectionName, array $payload = [], $sectionPath = null)
    {

//        if (! method_exists($this, 'onDisplay')) {
//            return;
//        }
        $this->setClass(($sectionPath ?? config('bradmin.user_path').'\\Sections\\') . $sectionName);

        if(!class_exists($this->getClass()))
        {
            throw new \Exception('Section ' . $this->getClass() . ' not found.');
        }

        $display = $this->app->call([$this->getClass(), 'onDisplay'], $payload);

        return $display;
    }

    public function fireCreate($sectionName, array $payload = [], $sectionPath = null)
    {
        $this->setClass(($sectionPath ?? config('bradmin.user_path').'\\Sections\\') . $sectionName);

        if(!class_exists($this->getClass()))
        {
            throw new \Exception('Section ' . $this->getClass() . ' not found.');
        }

        $display = $this->app->call([$this->getClass(), 'onCreate'], $payload);

        return $display;
    }

    public function fireEdit($sectionName, array $payload = [], $sectionPath = null)
    {
        $this->setClass(($sectionPath ?? config('bradmin.user_path').'\\Sections\\') . $sectionName);

        if(!class_exists($this->getClass()))
        {
            throw new \Exception('Section ' . $this->getClass() . ' not found.');
        }

        $display = $this->app->call([$this->getClass(), 'onEdit'], $payload);

        return $display;
    }

    public function fireDelete($sectionName, array $payload = [], $sectionPath = null)
    {
        $this->setClass(($sectionPath ?? config('bradmin.user_path').'\\Sections\\') . $sectionName);
        if(!class_exists($this->getClass()))
        {
            throw new \Exception('Section ' . $this->getClass() . ' not found.');
        }
        return $this->getClass();
    }

//    public function getTitle($sectionName)
//    {
//        $this->setClass(($sectionPath ?? config('bradmin.user_path').'\\Sections\\') . $sectionName);
//        $title = model
//
//        return $display;
//    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class): void
    {
        $this->class = $class;
    }

    public function isCreatable()
    {
//        return method_exists($this, 'onCreate') && parent::isCreatable($this->getModel());
        return true;
    }

    public function isEditable()
    {
//        return method_exists($this, 'onEdit') && parent::isEditable($model);
        return true;
    }

    public function isDeletable()
    {
//        return method_exists($this, 'onDelete') && parent::isDeletable($model);
        return true;
    }

    public function afterSave(Request $request, $model = null)
    {
        // override in child
    }

    public function beforeSave(Request $request, $model = null)
    {
        // override in child
    }

    public function beforeDelete(Request $request, $id = null)
    {
        // override in child
    }

}