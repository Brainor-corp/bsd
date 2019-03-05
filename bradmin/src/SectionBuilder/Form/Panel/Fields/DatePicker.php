<?php
/**
 * Created by PhpStorm.
 * User: Артем
 * Date: 02.10.2018
 * Time: 13:36
 */

namespace Bradmin\SectionBuilder\Form\Panel\Fields;


use Illuminate\Support\Facades\View;

class DatePicker
{
    private $name, $label, $value, $required, $format, $language, $todayBtn, $clearBtn, $minuteStep, $readonly;

    public function __construct($name, $label, $format = 'yyyy-mm-dd', $minuteStep = 1)
    {
        $this->setName($name);
        $this->setLabel($label);
        $this->setFormat($format);
        $this->setMinuteStep($minuteStep);
    }

    /**
     * @return mixed
     */
    public function getReadonly()
    {
        return $this->readonly;
    }

    /**
     * @param mixed $readonly
     */
    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinuteStep()
    {
        return $this->minuteStep;
    }

    /**
     * @param mixed $minuteStep
     */
    public function setMinuteStep($minuteStep)
    {
        $this->minuteStep = $minuteStep;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTodayBtn()
    {
        return $this->todayBtn;
    }


    /**
     * @param mixed $todayBtn
     */
    public function setTodayBtn($todayBtn)
    {
       $this->todayBtn = $todayBtn;
       return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @param mixed $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
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
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getClearBtn()
    {
        return $this->clearBtn;
    }

    /**
     * @param mixed $clearBtn
     */
    public function setClearBtn($clearBtn)
    {
        $this->clearBtn = $clearBtn;
        return $this;
    }


    public function render($value = null)
    {
        $name = $this->getName();
        $label = $this->getLabel();
        $required = $this->getRequired();
        $value = $value ?? $this->getValue();
        $format = $this->getFormat();
        $language = $this->getLanguage();
        $todayBtn = $this->getTodayBtn();
        $clearBtn = $this->getClearBtn();
        $minuteStep = $this->getMinuteStep();
        $readonly = $this->getReadonly();

        return View::make('bradmin::SectionBuilder/Form/Fields/datePicker')
            ->with(compact('name', 'label', 'value', 'required', 'format', 'language', 'todayBtn', 'clearBtn', 'minuteStep', 'readonly'));
    }
}