<?php
/**
 * Created by PhpStorm.
 * User: Артем
 * Date: 02.10.2018
 * Time: 13:36
 */

namespace Bradmin\SectionBuilder\Form\Panel\Fields;


use Illuminate\Support\Facades\View;

class DropZone
{
    private $name, $label, $id, $url;

    public function __construct($name, $label, $id, $url)
    {
        $this->setName($name);
        $this->setLabel($label);
        $this->setId($id);
        $this->setUrl($url);
    }

    /**
     * @param mixed $name
     * @return DropZone
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $label
     * @return DropZone
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param mixed $id
     * @return DropZone
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $url
     * @return DropZone
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function render()
    {
        $name = $this->getName();
        $label = $this->getLabel();
        $id = $this->getId();
        $url = $this->getUrl();

        return View::make('bradmin::SectionBuilder/Form/Fields/dropZone')
            ->with(compact('name', 'label','id','url'));
    }
}