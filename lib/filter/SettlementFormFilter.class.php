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
    }

    public function addCreditorIdColumnCriteria(Criteria $criteria, $field, $value)
    {
        $criteria->addJoin(SettlementPeer::CONTRACT_ID, ContractPeer::ID);
        $criteria->add(ContractPeer::CREDITOR_ID, $value);
        return $criteria;
    }
}
