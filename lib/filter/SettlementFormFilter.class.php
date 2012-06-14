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
        sfProjectConfiguration::getActive()->loadHelpers('Url');

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


        $this->setWidget('creditor_id', new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'order_by' => array('Lastname', 'asc'), 'add_empty' => true)));
        $this->setValidator('creditor_id', new sfValidatorPropelChoice(array('model' => 'Creditor', 'required' => false)));
        $this->getWidgetSchema()->moveField('creditor_id', sfWidgetFormSchema::FIRST);
        $contract = ContractPeer::retrieveByPK($this->getValue('contract_id'));
        if ($contract) {
            $this->getWidgetSchema()->setDefault('creditor_id', $contract->getCreditorId());
        }
        $this->getWidget('creditor_id')->setAttribute('onchange', sprintf("updateSelectBox('%s','%s','%s', '%s'); ;", url_for('@update_contract_select?form_name=settlement_filters'), 'settlement_filters_creditor_id', 'settlement_filters_contract_id', 'creditor_id'));

        $settlementTypeChoices = $this->getSettlementTypeChoices();
        $this->setWidget('settlement_type', new sfWidgetFormChoice(array('choices'=>$settlementTypeChoices)));
        $this->setValidator('settlement_type', new sfValidatorChoice(array('choices'=>  array_keys($settlementTypeChoices), 'required'=>false)));
    }

    public function addCreditorIdColumnCriteria(Criteria $criteria, $field, $value)
    {
        $criteria->addJoin(SettlementPeer::CONTRACT_ID, ContractPeer::ID);
        $criteria->add(ContractPeer::CREDITOR_ID, $value);
        return $criteria;
    }

    public function addSettlementTypeColumnCriteria(Criteria $criteria, $field, $value)
    {
        $criteria->add(SettlementPeer::SETTLEMENT_TYPE, $value);
        return $criteria;
    }

    public function addCashColumnCriteria(Criteria $criteria, $field, $value)
    {
        if($value == 1)
        {
            $criteria->add(SettlementPeer::CASH, true);
        }
        elseif($value == 0)
        {
            $criteria->add(SettlementPeer::CASH, false);
        }
        return $criteria;
    }

    protected function getSettlementTypeChoices()
    {
        $settlementTypes = array(
            SettlementPeer::IN_PERIOD,
            SettlementPeer::END_OF_FIRST_YEAR,
            SettlementPeer::MANUAL,
            SettlementPeer::CLOSING,
        );
        $translateService = ServiceContainer::getTranslateService();
        $choices = array(''=>'');
        foreach($settlementTypes as $settlementType)
        {
            $choices[$settlementType] = $translateService->__($settlementType);
        }

        return $choices;
    }
}
