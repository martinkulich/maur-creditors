<?php

class ClosingSettlementForm extends SettlementForm
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $fieldsToUnset = array(
            'creditor_id',
            'capitalized',
            'balance_reduction',
            'contract_id',
        );

        $this->getWidget('paid')->setLabel('To pay');

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

        $contract = $this->getObject()->getContract();
        $closingAmount = ServiceContainer::getContractService()->getContractClosingAmount($contract);
        $lastDate = $contract->getLastSettlementDate();
        $this->getObject()->setPaid($closingAmount);
        $this->getWidget('date')->setLabel('Settlment Date');
        $this->getWidget('date')->setAttribute('onChange', sprintf("calculateContractClosingAmount('#contract_closing_settlement_date_date','#contract_closing_settlement_paid', '%s')", url_for('@contact_closing_amount?id='.$contract->getId())));

    }
}