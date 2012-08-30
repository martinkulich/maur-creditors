<?php

/**
 * Project form base class.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseFormPropel extends sfFormPropel
{

    public function setup()
    {

    }

    public function unsetField($field)
    {
        unset($this->validatorSchema[$field]);
        unset($this->widgetSchema[$field]);
    }

    public function getParentFormName()
    {
        return null;
    }


    public function getFullFormName()
    {
        $fullFormName = $this->getName();
        if($parentFormName = $this->getParentFormName())
        {
            $fullFormName = $parentFormName.'_'.$fullFormName;
        }

        return $fullFormName;
    }
}
