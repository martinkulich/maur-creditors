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

        $dateFields = array(
            'date',
            'date_of_payment'
        );
        foreach ($dateFields as $filed) {
            $this->setWidget($filed, new myJQueryDateWidget());
            $this->setValidator($filed, new myValidatorDate());
        }
        $this->getValidator('date_of_payment')->setOption('required', false);
        $this->getWidgetSchema()->moveField('date_of_payment', sfWidgetFormSchema::AFTER, 'date');
        $this->getWidget('date')->setLabel('Date of settlement');

        $contractId = $this->getObject()->getContractId() ? $this->getObject()->getContractId() : 0;

        if ($contractId) {
            $now = new DateTime('now');
            $this->getWidget('date')->setDefault($now->format('Y-m-d'));
        }


        if ($this->getObject()->isNew() && $contractId) {

            $this->getObject()->setDate($now);
        }

        $fullFormName = $this->getFullFormName();

        $dateOnChange = "
            calculateSettlement(%settlement_id%, '%contract_field_selector%', %contract_id%, '%date_field_selector%','%interest_field_selector%', '%interest_url%', '%manual_interest_field_selector%');
            calculateSettlement(%settlement_id%, '%contract_field_selector%', %contract_id%, '%date_field_selector%','%balance_field_selector%', '%balance_url%', '%manual_balance_field_selector%');
            ";
        $replacements = array(
            '%contract_id%' => $contractId,
            '%interest_url%' => url_for('@settlement_interest'),
            '%balance_url%' => url_for('@settlement_balance'),
            '%date_field_selector%' => '#' . $fullFormName . '_date_date',
            '%contract_field_selector%' => '#' . $fullFormName . '_contract_id',
            '%interest_field_selector%' => '#' . $fullFormName . '_interest',
            '%balance_field_selector%' => '#' . $fullFormName . '_balance',
            '%manual_interest_field_selector%' => '#' . $fullFormName . '_manual_interest',
            '%manual_balance_field_selector%' => '#' . $fullFormName . '_manual_balance',
            '%settlement_id%' => $this->getObject()->isNew() ? 0 : $this->getObject()->getId(),
        );
        $dateOnChange = str_replace(array_keys($replacements), $replacements, $dateOnChange);
        $this->getWidget('date')->setAttribute('onChange', $dateOnChange);

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


        $fieldsToUnset = array(
            'settlement_type',
        );

        $activableFields = array(
            'interest',
            'balance'
        );


        foreach ($activableFields as $field) {
            $onUncheck = "calculateSettlement(%settlement_id%, '%contract_field_selector%', %contract_id%, '%date_field_selector%','#%selector%', '%url%');";
            $replacements = array(
                '%contract_id%' => $contractId,
                '%url%' => url_for('@settlement_' . $field),
                '%selector%' => $fullFormName . '_' . $field,
                '%contract_field_selector%' => '#' . $fullFormName . '_contract_id',
                '%date_field_selector%' => '#' . $fullFormName . '_date_date',
                '%settlement_id%' => $this->getObject()->isNew() ? 0 : $this->getObject()->getId(),
            );
            $onUncheck = str_replace(array_keys($replacements), $replacements, $onUncheck);

            $checkboxWidgetName = 'manual_' . $field;
            $getter = sfInflector::camelize('get_' . $checkboxWidgetName);
            $this->setWidget($field, new myWidgetFormActivableInput(array('on_uncheck' => $onUncheck, 'checked' => $this->getObject()->$getter(), 'widget_name' => $checkboxWidgetName, 'widget' => $this->getWidget($checkboxWidgetName), 'form_name'=>$this->getName(), 'parent_form_name'=>$this->getParentFormName())));

            $widgetSchema = $this->getWidgetSchema();
            unset($widgetSchema[$checkboxWidgetName]);
        }

        //zatim nechat moznost vzdy editovat
        if ($this->getObject()->getSettlementType() != SettlementPeer::MANUAL && false) {
            $fieldsToUnset[] = 'interest';
            $fieldsToUnset[] = 'balance';
            $fieldsToUnset[] = 'manual_balance';
            $fieldsToUnset[] = 'manual_interest';
        }

        if (!in_array($this->getObject()->getSettlementType(), array(SettlementPeer::MANUAL, SettlementPeer::CLOSING))) {
            $fieldsToUnset[] = 'date';
        }

        if (!$this->getObject()->isNew()) {
            $fieldsToUnset[] = 'creditor_id';
            $fieldsToUnset[] = 'contract_id';
        }

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
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

            $settlementType = $this->getValue('settlement_type');
            if (!$settlementType) {
                $settlementType = SettlementPeer::MANUAL;
            }
            $settlement->setSettlementType($settlementType);
            $settlement->setDate($this->getValue('date'));
            if (!$this->getValue('manual_balance')) {
                $settlement->setBalance($contractService->getBalanceForSettlement($settlement));
            }

            if (!$this->getValue('manual_interest')) {
                $settlement->setInterest($contractService->getInterestForSettlement($settlement));
            }
        }
        parent::doSave($con);
        $settlement->reload();
        $contractService->checkContractChanges($settlement->getContract());
    }
}
