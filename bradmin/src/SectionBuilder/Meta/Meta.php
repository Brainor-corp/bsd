<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 25.10.2018
 * Time: 11:21
 */

namespace Bradmin\SectionBuilder\Meta;

class Meta
{
    private $styles = [];
    private $scripts = [];

    /**
     * @return array
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * @param array $styles
     * @return Meta
     */
    public function setStyles($styles)
    {
        $this->styles = $styles;
        return $this;
    }

    /**
     * @return array
     */
    public function getScripts()
    {
        return $this->scripts;
    }

    /**
     * @param array $scripts
     * @return Meta
     */
    public function setScripts($scripts)
    {
        $this->scripts = $scripts;
        return $this;
    }


}