<?php

class SettlementPostValidator extends sfValidatorBase
{

    public function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);

        $this->addMessage('paid_date_of_payment_error', 'Date of payment required if paid is set');
    }

    public function doClean($values)
    {
        $errorSchema = new sfValidatorErrorSchema($this);

        $paid = array_key_exists('paid', $values) ? $values['paid'] : null;
        $dateOfPayment = array_key_exists('date_of_payment', $values) ? $values['date_of_payment'] : null;

        if ($paid && !$dateOfPayment) {
            $error = new sfValidatorError($this, 'paid_date_of_payment_error');
            $errorSchema->addError($error, 'date_of_payment');
        }

        if ($errorSchema->count() > 0) {

            throw $errorSchema;
        }
        return $values;
    }
}
