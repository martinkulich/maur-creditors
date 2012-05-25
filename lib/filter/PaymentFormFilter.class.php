<?php

/**
 * Payment filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class PaymentFormFilter extends BasePaymentFormFilter
{

    public function configure()
    {
        $this->setWidget('date', new MyJQueryFormFilterDate());

        $this->setValidator('date', new MyValidatorDateRange(array('required' => false)));

        $this->unsetField('amount');
    }
}
