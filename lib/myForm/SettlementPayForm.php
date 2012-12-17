<?php

class SettlementPayForm extends SettlementForm
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $unsetAbleFields = array(
            'outgoing_payment_id',
            'paid',
        );

        $fieldsToUnset = array_diff(array_keys($this->getWidgetSchema()->getFields()), $unsetAbleFields);

        $this->getWidget('paid')->setLabel('To pay');

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

    }
}