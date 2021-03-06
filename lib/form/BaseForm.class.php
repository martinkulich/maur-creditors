<?php

/**
 * Base project form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{

    public function configure()
    {
        $this->disableCSRFProtection();
        parent::configure();
    }

    public function unsetField($field)
    {
        unset($this->validatorSchema[$field]);
        unset($this->widgetSchema[$field]);
    }    
}
