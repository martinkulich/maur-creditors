<?php

class SettlementAllocateForm extends AllocationForm
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $fieldsToUnset = array(
            'settlement_id',
            'contract_id',
            'creditor_id',
        );


        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

    }
}