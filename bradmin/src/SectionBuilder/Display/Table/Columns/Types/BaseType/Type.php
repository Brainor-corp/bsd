<?php
/**
 * Created by PhpStorm.
 * User: Артем
 * Date: 01.10.2018
 * Time: 13:30
 */

namespace Bradmin\SectionBuilder\Display\Table\Columns\Types\BaseType;


abstract class Type
{
    private $label, $name, $sortable;

    public function __construct($name, $label)
    {
        $this->setLabel($label);
        $this->setName($name);
        $this->setSortable(false);
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getSortable()
    {
        return $this->sortable;
    }

    /**
     * @param mixed $sortable
     * @return Type
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
        return $this;
    }

    public abstract function render($value);
}