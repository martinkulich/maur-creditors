<?php

class CreditorGiftForm extends GiftForm
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $fieldsToUnset = array(
            'creditor_id',
        );


        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

    }
}