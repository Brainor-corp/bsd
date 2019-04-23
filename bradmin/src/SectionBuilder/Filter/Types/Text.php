<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 04.12.2018
 * Time: 12:28
 */

namespace Bradmin\SectionBuilder\Filter\Types;

use Illuminate\Support\Facades\View;

class Text
{
    private $name, $placeholder, $isLike;

    public function __construct($name, $placeholder, $isLike = true)
    {
        $this->setName($name);
        $this->setIsLike($isLike);
        $this->setPlaceholder($placeholder);
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
     * @return Text
     */
    public function setIsLike($isLike): Text
    {
        $this->isLike = $isLike;
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

    public function render()
    {
        $name = $this->getName();
        $placeholder = $this->getPlaceholder();
        $isLike = $this->isLike();

        return View::make('bradmin::SectionBuilder/Filter/text')->with(compact('name', 'placeholder', 'isLike'));
    }
}