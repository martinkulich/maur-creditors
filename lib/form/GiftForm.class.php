<?php

/**
 * Gift form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class GiftForm extends BaseGiftForm
{
    public function configure()
    {
        $this->getWidgetSchema()->moveField('creditor_id', sfWidgetFormSchema::FIRST);
        $this->setWidget('date', new myJQueryDateWidget());
        $this->setValidator('date', new myValidatorDate());
    }
}
