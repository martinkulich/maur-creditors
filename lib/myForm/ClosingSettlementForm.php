<?php

class ClosingSettlementForm extends SettlementForm
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $fieldsToUnset = array(
            'creditor_id',
        );


        $this->setWidget('contract_id', new sfWidgetFormInputHidden());
        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

    }
}