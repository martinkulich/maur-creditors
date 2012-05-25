<?php

/**
 * Settlement filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class SettlementFormFilter extends BaseSettlementFormFilter
{

    public function configure()
    {
        $this->setWidget('date', new MyJQueryFormFilterDate());
        $this->setValidator('date', new MyValidatorDateRange(array('required' => false)));

        $fieldsToUnset = array(
            'balance',
            'interest',
            'capitalized',
            'balance',
            'paid',
        );



        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }
    }
}
