<?php

/**
 * OutgoingPayment filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class OutgoingPaymentFormFilter extends BaseOutgoingPaymentFormFilter
{

    public function configure()
    {
        $this->setWidget('date', new myJQueryDateWidget());
        $this->setValidator('date', new myValidatorDate());
        
    }

}
