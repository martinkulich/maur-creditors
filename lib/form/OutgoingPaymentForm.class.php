<?php

/**
 * OutgoingPayment form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class OutgoingPaymentForm extends BaseOutgoingPaymentForm
{

    public function configure()
    {
        $this->setWidget('date', new myJQueryDateWidget());
        $this->setValidator('date', new myValidatorDate());
        
        $this->getWidget('creditor_id')->setOption('order_by', array('Lastname', 'asc'));

        $field = 'amount';
        if (!$this->getObject()->isNew()) {
            $this->setWidget($field, new myWidgetFormInputAmount(array('currency_code' => $this->getObject()->getCurrencyCode())));
            $this->setValidator($field, new myValidatorNumber());
        } else {
            $this->changeFieldToMyNumberField($field);
        }
    }

}
