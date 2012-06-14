<?php

class ClosingSettlementForm extends SettlementForm
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

        $this->setWidget('contract_id', new sfWidgetFormInputHidden());
        $contract = $this->getObject()->getContract();
        var_dump($contract->getId());
        $this->setDefault('contract_id', $contract->getId());
        $unsettled = $contract->getUnsettled();
        $lastDate = $contract->getLastSettlementDate();
        $this->getWidget('date')->setLabel('Settlment Date');
        $this->getWidget('date')->setAttribute('onChange', sprintf("calculateUnsettledAmount('#contract_closing_settlement_date_date','#contract_closing_settlement_paid', '%s')", url_for('@unsettled_amount?id='.$contract->getId())));

    }
}