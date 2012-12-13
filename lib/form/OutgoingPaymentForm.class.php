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

        $field = 'amount';
        if (!$this->getObject()->isNew()) {
            $this->setWidget($field, new myWidgetFormInputAmount(array('currency_code' => $this->getObject()->getCurrencyCode())));
            $this->setValidator($field, new myValidatorNumber());
        } else {
            $this->changeFieldToMyNumberField($field);
        }
    }

}
