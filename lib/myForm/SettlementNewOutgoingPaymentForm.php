<?php

class SettlementNewOutgoingPaymentForm extends OutgoingPaymentForm
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $fieldsToUnset = array(
            'creditor_id',
            'currency_code',
        );


        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

    }
}