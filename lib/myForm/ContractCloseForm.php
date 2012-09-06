<?php

class ContractCloseForm extends ContractForm
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $fieldsToUnset = array(
            'creditor_id',
            'activated_at',
            'created_at',
            'period',
            'interest_rate',
            'amount',
            'name',
            'currency_code',
            'note',
        );

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

        $this->setWidget('closed_at', new myJQueryDateWidget());
        $this->setValidator('closed_at', new myValidatorDate());

        $contract = $this->getObject();
        $closingSettlement = new Settlement();
        $closingSettlement->setSettlementType(SettlementPeer::CLOSING);
        $closingSettlement->setContract($contract);
        $closingSettlementForm = new ClosingSettlementForm($closingSettlement);
        $this->embedForm('closing_settlement', $closingSettlementForm);
    }

    public function doSave($con = null)
    {
        $contractService = ServiceContainer::getContractService();
        $closingSettlementValues = $this->getValue('closing_settlement');
        $closingSettlementForm = $this->getEmbeddedForm('closing_settlement');

        $closingSettlementForm->updateObject($closingSettlementValues);
        $closingSettlement = $closingSettlementForm->getObject();
        $closingSettlement->save($con);
        $closingSettlementForm->deleteSettlementOfSameDay();

        parent::doSave($con);
    }
}