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
        sfProjectConfiguration::getActive()->loadHelpers('Url');

        $this->getWidgetSchema()->moveField('balance', sfWidgetFormSchema::AFTER, 'date');
        $this->getWidgetSchema()->moveField('interest', sfWidgetFormSchema::AFTER, 'balance');
        $this->getWidgetSchema()->moveField('note', sfWidgetFormSchema::LAST);

        $this->getWidget('note')->setAttribute('rows', 2);

        $this->setWidget('date', new myJQueryDateWidget());
        $this->setValidator('date', new myValidatorDate());

        $fieldsToUnset = array(
            'settlement_type',
        );

        if($this->getObject()->getSettlementType() != SettlementPeer::MANUAL)
        {
            $fieldsToUnset[] = 'interest';
            $fieldsToUnset[] = 'balance';

        }

        if($this->getObject()->getSettlementType() == SettlementPeer::END_OF_FIRST_YEAR)
        {
            $fieldsToUnset[] = 'date';

        }

        if (!$this->getObject()->isNew()) {
//            $fieldsToUnset[] = 'date';
        }

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

        if (!$this->getObject()->isNew()) {
            $amountFields = array(
                'paid',
                'capitalized',
                'balance_reduction',
            );

            foreach ($amountFields as $field) {
                $this->setWidget($field, new myWidgetFormInputAmount(array('currency_code' => $this->getObject()->getContract()->getCurrencyCode())));
            }
        }

        $contractCriteria = new Criteria();
        $contractCriteria->addJoin(SettlementPeer::CONTRACT_ID, ContractPeer::ID);
        $contractCriteria->add(ContractPeer::ACTIVATED_AT, null, Criteria::ISNOTNULL);

        $contractWidget = $this->getWidget('contract_id');
        $contractWidget->setOption('add_empty', true);
        $contractWidget->setOption('criteria', $contractCriteria);
        $this->getValidator('contract_id')->setOption('criteria', $contractCriteria);

        $this->setWidget('creditor_id', new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'order_by' => array('Lastname', 'asc'), 'add_empty' => true)));
        $this->setValidator('creditor_id', new sfValidatorPropelChoice(array('model' => 'Creditor', 'required' => true)));
        $this->getWidgetSchema()->moveField('creditor_id', sfWidgetFormSchema::FIRST);
        $contract = ContractPeer::retrieveByPK($this->getObject()->getContractId());
        if ($contract) {
            $this->getWidgetSchema()->setDefault('creditor_id', $contract->getCreditorId());
            $this->getWidgetSchema()->setDefault('bank_account', $contract->getCreditor()->getBankAccount());
        }
        $this->getWidget('creditor_id')->setAttribute('onchange', sprintf("updateSelectBox('%s','%s','%s', '%s'); ;", url_for('@update_contract_select?form_name=settlement'), 'settlement_creditor_id', 'settlement_contract_id', 'creditor_id'));


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

            $settlement->setSettlementType(SettlementPeer::MANUAL);
            $settlement->setDate($this->getValue('date'));
            $settlement->setBalance($contractService->getBalanceForSettlement($settlement));
            $settlement->setInterest($contractService->getInterestForSettlement($settlement));
        }
        parent::doSave($con);
        $settlement->reload();
        $contractService->checkContractChanges($settlement->getContract());
    }
}
