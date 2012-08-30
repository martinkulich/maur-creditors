<?php

class ClosingSettlementForm extends SettlementForm
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $fieldsToUnset = array(
            'creditor_id',
            'capitalized',
            'contract_id',
        );

        $this->getWidget('paid')->setLabel('To pay');

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

        $contract = $this->getObject()->getContract();
        $closingAmount = ServiceContainer::getContractService()->getContractClosingAmount($contract, null, true);
        $lastDate = $contract->getLastSettlementDate();
        $this->getObject()->setPaid($closingAmount['unsettled']);
        $this->getObject()->setBalanceReduction($closingAmount['balance_reduction']);
        $this->getWidget('date')->setLabel('Settlment Date');
        $onChange = sprintf("calculateContractClosingAmount('#contract_closing_settlement_date_date','#contract_closing_settlement_paid','#contract_closing_settlement_balance_reduction', '%s'); ", url_for('@contact_closing_amount?id='.$contract->getId()));
        $parentOnChange =  $this->getWidget('date')->getAttribute('onChange');

        if($parentOnChange)
        {
            $onChange .= $parentOnChange;
        }
        $this->getWidget('date')->setAttribute('onChange', $onChange);

    }

    public function getParentFormName()
    {
        return 'contract';
    }
    public function getName()
    {
        return 'closing_settlement';
    }
}