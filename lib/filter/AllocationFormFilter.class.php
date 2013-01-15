<?php

/**
 * Allocation filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class AllocationFormFilter extends BaseAllocationFormFilter
{
    public function configure()
    {
        sfProjectConfiguration::getActive()->loadHelpers('Url');

        $this->setWidget('creditor_id', new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'order_by' => array('Lastname', 'asc'), 'add_empty' => true)));
        $this->setValidator('creditor_id', new sfValidatorPropelChoice(array('model' => 'Creditor', 'required' => false)));

        $this->getWidgetSchema()->moveField('creditor_id', sfWidgetFormSchema::FIRST);
        $this->getWidgetSchema()->moveField('outgoing_payment_id', sfWidgetFormSchema::AFTER, 'creditor_id');

        $this->getWidget('creditor_id')->setAttribute('onchange', sprintf(
            "updateSelectBox('%s','%s','%s', '%s'); updateSelectBox('%s','%s','%s', '%s');",
            url_for('@update_contract_select?form_name=allocation_filters'),
            'allocation_filters_creditor_id',
            'allocation_filters_contract_id',
            'creditor_id',
            url_for('@update_outgoing_payment_select?form_name=allocation_filters'),
            'allocation_filters_creditor_id',
            'allocation_filters_outgoing_payment_id',
            'creditor_id'
        ));

        $this->setWidget('contract_id', new sfWidgetFormPropelChoice(array('model' => 'Contract', 'order_by' => array('Name', 'asc'), 'add_empty' => true)));
        $this->setValidator('contract_id', new sfValidatorPropelChoice(array('model' => 'Contract', 'required' => false)));
        $this->getWidgetSchema()->moveField('contract_id', sfWidgetFormSchema::AFTER, 'outgoing_payment_id');
        $this->getWidget('contract_id')->setAttribute('onchange', sprintf(
            "updateSelectBox('%s','%s','%s', '%s');",
            url_for('@update_settlement_select?form_name=allocation_filters'),
            'allocation_filters_contract_id',
            'allocation_filters_settlement_id',
            'contract_id'
        ));


        $this->getWidgetSchema()->moveField('settlement_id', sfWidgetFormSchema::AFTER, 'contract_id');


        $fieldsToUnset = array(
            'paid',
            'balance_reduction'
        );


        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }
    }

    public function addCreditorIdColumnCriteria(Criteria $criteria, $field, $value)
    {
        return $criteria
            ->addJoin(AllocationPeer::SETTLEMENT_ID, SettlementPeer::ID)
            ->addJoin(SettlementPeer::CONTRACT_ID, ContractPeer::ID)
            ->add(ContractPeer::CREDITOR_ID, $value);
    }

    public function addContractIdColumnCriteria(Criteria $criteria, $field, $value)
    {
        return $criteria
            ->addJoin(AllocationPeer::SETTLEMENT_ID, SettlementPeer::ID)
            ->add(SettlementPeer::CONTRACT_ID, $value);
    }


}
