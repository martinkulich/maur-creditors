<?php

/**
 * Payment form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class PaymentForm extends BasePaymentForm
{

    public function configure()
    {
        $this->setWidget('date', new myJQueryDateWidget());
        $this->setValidator('date', new myValidatorDate());

        if ($this->getObject()->getContractId()) {
            $this->unsetField('contract_id');
        }
    }
}
