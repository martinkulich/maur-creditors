<?php

/**
 * Project filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseFormFilterPropel extends sfFormFilterPropel
{

    public function setup()
    {
        $this->disableCSRFProtection();
    }

    public function unsetField($field)
    {
        unset($this->validatorSchema[$field]);
        unset($this->widgetSchema[$field]);
    }

}
