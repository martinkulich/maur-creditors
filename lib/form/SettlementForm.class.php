<?php

/**
 * Settlement form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class SettlementForm extends BaseSettlementForm
{

    public function configure()
    {

        if ($this->getObject()->getContractId()) {
            $this->unsetField('contract_id');
        }

        $this->setWidget('date', new myJQueryDateWidget());
        $this->setValidator('date', new myValidatorDate());

        $fieldsToUnset = array(
            'balance',
            'interest',
        );


        if (!$this->getObject()->isNew()) {
            $fieldsToUnset[] = 'date';
        }

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

        $amountFields = array(
            'paid',
            'capitalized',
            'balance_reduction',
        );

        foreach ($amountFields as $field) {
            $this->setWidget($field, new myWidgetFormInputAmount());
        }
    }

    public function doSave($con = null)
    {
        $contractService = ServiceContainer::getContractService();
        $settlement = $this->getObject();
        if ($settlement->isNew()) {
            $contract = ContractPeer::retrieveByPK($this->getValue('contract_id'));
            if ($contract) {
                $settlement->setContract($contract);
            }

            $settlement->setDate($this->getValue('date'));
            $settlement->setBalance($contractService->getBalanceForSettlement($settlement));
            $settlement->setInterest($contractService->getInterestForSettlement($settlement));
        }
        parent::doSave($con);

        $contractService->updateContractSettlements($settlement->getContract());
    }
}
