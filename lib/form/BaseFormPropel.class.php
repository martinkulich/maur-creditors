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

}
