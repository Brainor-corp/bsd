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
    private $name, $placeholder, $options, $modelForOptions, $queryFunctionForModel, $field, $display, $isLike;

    public function __construct($name, $field = 'id')
    {
        $this->setName($name);
        $this->setField($field);
    }

    /**
     * @return mixed
     */
    public function isLike()
    {
        return $this->isLike;
    }

    /**
     * @param mixed $isLike
     * @return Select
     */
    public function setIsLike($isLike): Select
    {
        $this->isLike = $isLike;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * @param mixed $display
     * @return Select
     */
    public function setDisplay($display): Select
    {
        $this->display = $display;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQueryFunctionForModel()
    {
        return $this->queryFunctionForModel;
    }

    /**
     * @param mixed $queryFunctionForModel
     * @return Select
     */
    public function setQueryFunctionForModel($queryFunctionForModel)
    {
        $this->queryFunctionForModel = $queryFunctionForModel;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     * @return Select
     */
    public function setField($field): Select
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModelForOptions()
    {
        return $this->modelForOptions;
    }

    /**
     * @param mixed $modelForOptions
     * @return Select
     */
    public function setModelForOptions($modelForOptions): Select
    {
        $this->modelForOptions = $modelForOptions;
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
     * @param mixed $name
     * @return Select
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
     * @return Select
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
        $field = $this->getField();

        if(isset($this->options))
        {
            return $this->options;
        } else
        {
            if($this->getModelForOptions() !== null)
            {
                foreach ($this->getModelForOptions()::when(
                    !empty($this->getQueryFunctionForModel()),
                    $this->getQueryFunctionForModel()
                )->get() as $row) {
                    $this->options[$row->{$field}] = $row->{$this->getDisplay()};
                }
            }

            return $this->options;
        }
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
        $isLike = $this->isLike();

        return View::make('bradmin::SectionBuilder/Filter/select')->with(compact('name', 'options', 'isLike'));
    }
}