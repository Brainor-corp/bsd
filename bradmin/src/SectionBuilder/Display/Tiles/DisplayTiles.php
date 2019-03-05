<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 30.10.2018
 * Time: 10:53
 */

namespace Bradmin\SectionBuilder\Display\Tiles;

use Bradmin\Section;
use Bradmin\SectionBuilder\Meta\Meta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use BRHelper;

class DisplayTiles
{
    private $pagination, $elements, $scopes, $meta, $nav;

    public function __construct($elements, $pagination)
    {
        $this->setPagination($pagination);
        $this->setElements($elements);
        $this->meta = new Meta;
    }

    public function render($modelPath, Section $firedSection, $pluginData = null, $request = null)
    {
        $elements = $this->getElements();
        $relationData = null;

        foreach ($elements as $element)
        {
            $exp = explode('.', $element->getName());
            if(count($exp) > 1)
            {
                $relationData[] = implode(".", array_slice($exp, 0, -1));
            }
        }


        $model = new $modelPath();

        if(!empty($this->getScopes()))
        {
            foreach ($this->getScopes() as $scope)
            {
                $model = $model->{$scope}();
            }
        }

        $data = $model->when(isset($relationData), function ($query) use ($relationData) {
            $query->with($relationData);
        })->paginate($this->getPagination());
        $fields = array();

        foreach ($data as $key => $row)
        {
            foreach ($elements as $element)
            {
                $names = explode('.', $element->getName());

                $rowVal = $row;
                foreach ($names as $name)
                {
                    if(!(is_array($rowVal) || $rowVal instanceof \Countable))
                    {
                        $rowVal = $rowVal->{$name} ?? null;
                    } else
                    {
                        break;
                    }
                }

                if($element->isHeaderImage()[0])
                {
                    $fields[$key]['image'] = $rowVal;
                }
                if($element->isHeaderImage()[1])
                {
                    $fields[$key][$element->getName()] = $element->render($rowVal);
                }

            }
            $fields[$key]['brRowId'] = $row->id;
        }

        if(isset($pluginData['redirectUrl']))
        {
            $rc = new \ReflectionClass($firedSection);
            $pluginData['redirectUrl'] = strtr($pluginData['redirectUrl'], ['{sectionName}' => $rc->getShortName()]);
        }

        $nav = self::getNav();

        $response['data'] = $data;
        $response['view'] = View::make('bradmin::SectionBuilder/Display/Tiles/tiles')->with(compact('data', 'elements', 'fields', 'firedSection', 'pluginData', 'nav'));

        return $response;
    }

    /**
     * @return mixed
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @param mixed $pagination
     * @return Tiles
     */
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param mixed $elements
     * @return Tiles
     */
    public function setElements($elements)
    {
        $this->elements = $elements;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @param mixed $scopes
     * @return Tiles
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param mixed $meta
     * @return Tiles
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNav()
    {
        return $this->nav;
    }

    /**
     * @param mixed $nav
     * @return DisplayTiles
     */
    public function setNav($nav)
    {
        $this->nav = $nav;
        return $this;
    }
}