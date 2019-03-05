<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 04.12.2018
 * Time: 12:28
 */

namespace Bradmin\SectionBuilder\Filter\Types;

use Illuminate\Support\Facades\View;

class Select
{
    private $name, $placeholder, $options;

    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Text
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param mixed $placeholder
     * @return Text
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     * @return Select
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function render()
    {
        $name = $this->getName();
        $options = $this->getOptions();

        return View::make('bradmin::SectionBuilder/Filter/select')->with(compact('name', 'options'));
    }
}