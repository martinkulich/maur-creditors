<?php

/**
 * Unpaid filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class UnpaidFormFilter extends BaseUnpaidFormFilter
{

    public function configure()
    {
        sfProjectConfiguration::getActive()->loadHelpers('Url');

        $fieldsToUnset = array(
            'contract_name',
            'creditor_fullname',
            'settlement_date',
            'contract_unpaid',
            'creditor_unpaid'
        );

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

//        $this->setWidget('date', new myJQueryDateWidget());
//        $this->setValidator('date', new myValidatorDate(array('required'=>false)));


        $this->setWidget('creditor_id', new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'order_by' => array('Lastname', 'asc'), 'add_empty' => true)));
        $this->setValidator('creditor_id', new sfValidatorPropelChoice(array('model' => 'Creditor', 'required' => false)));
        $this->getWidgetSchema()->moveField('creditor_id', sfWidgetFormSchema::FIRST);
        $contract = ContractPeer::retrieveByPK($this->getValue('contract_id'));
        if ($contract) {
            $this->getWidgetSchema()->setDefault('creditor_id', $contract->getCreditorId());
        }
        $this->getWidget('creditor_id')->setAttribute('onchange', sprintf("updateSelectBox('%s','%s','%s', '%s', 'all'); ;", url_for('@update_contract_select?form_name=unpaid_filters'), 'unpaid_filters_creditor_id', 'unpaid_filters_contract_id', 'creditor_id'));
    }

//    public function addDateColumnCriteria(Criteria $criteria, $field, $value)
//    {
//        $customCriteria = sprintf("%s = (SELECT MAX(%s) FROM %s WHERE %s <= '%s')",  UnpaidPeer::SETTLEMENT_DATE, SettlementPeer::DATE, SettlementPeer::TABLE_NAME, SettlementPeer::DATE, $value);
//        $criteria->add(UnpaidPeer::SETTLEMENT_DATE, $customCriteria, Criteria::CUSTOM);
//        return $criteria;
//    }

    public function addContractIdColumnCriteria(Criteria $criteria, $field, $value)
    {
        $criteria->add(UnpaidPeer::CONTRACT_ID, $value);
        return $criteria;
    }

    public function doBuildCriteria(array $values)
    {
        $criteria = parent::doBuildCriteria($values);
        $customCriteria = sprintf("%s = (SELECT MAX(s1.date) FROM settlement s1 WHERE s1.date <= '%s' AND s1.contract_id = %s)", UnpaidPeer::SETTLEMENT_DATE, date('Y-m-d'), UnpaidPeer::CONTRACT_ID);
        $criteria->add(UnpaidPeer::SETTLEMENT_DATE, $customCriteria, Criteria::CUSTOM);
        $criteria->add(UnpaidPeer::CONTRACT_UNPAID, 0, Criteria::NOT_EQUAL);
        return $criteria;
        
    }
}
