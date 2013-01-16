<?php

/**
 * Allocation form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class AllocationForm extends BaseAllocationForm
{
    public function configure()
    {
        sfProjectConfiguration::getActive()->loadHelpers('Url');

        $this->setWidget('creditor_id', new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'order_by' => array('Lastname', 'asc'), 'add_empty' => true)));
        $this->setValidator('creditor_id', new sfValidatorPropelChoice(array('model' => 'Creditor', 'required' => true)));

        $this->getWidgetSchema()->moveField('creditor_id', sfWidgetFormSchema::FIRST);
        $this->getWidgetSchema()->moveField('outgoing_payment_id', sfWidgetFormSchema::AFTER, 'creditor_id');

        $this->getWidget('creditor_id')->setAttribute('onchange', sprintf(
            "updateSelectBox('%s','%s','%s', '%s'); updateSelectBox('%s','%s','%s', '%s');",
            url_for('@update_contract_select?form_name=allocation'),
            'allocation_creditor_id',
            'allocation_contract_id',
            'creditor_id',
            url_for('@update_outgoing_payment_select?form_name=allocation'),
            'allocation_creditor_id',
            'allocation_outgoing_payment_id',
            'creditor_id'
        ));

        $this->setWidget('contract_id', new sfWidgetFormPropelChoice(array('model' => 'Contract', 'order_by' => array('Name', 'asc'), 'add_empty' => true)));
        $this->setValidator('contract_id', new sfValidatorPropelChoice(array('model' => 'Contract', 'required' => true)));
        $this->getWidgetSchema()->moveField('contract_id', sfWidgetFormSchema::AFTER, 'outgoing_payment_id');
        $this->getWidget('contract_id')->setAttribute('onchange', sprintf(
            "updateSelectBox('%s','%s','%s', '%s');",
            url_for('@update_settlement_select?form_name=allocation'),
            'allocation_contract_id',
            'allocation_settlement_id',
            'contract_id'
        ));


        $creditor = null;
        if($this->getObject()->getContract())
        {
            $creditor = $this->getObject()->getContract()->getCreditor();
        }
        elseif($this->getObject()->getSettlement())
        {
            $creditor = $this->getObject()->getSettlement()->getContract()->getCreditor();
        }

        $outgoingPaymentCriteria = ServiceContainer::getAllocationService()->getAllocableOutgoingPaymentsCriteria($creditor, $this->getObject()->getOutgoingPayment());

        $this->getWidget('outgoing_payment_id')
            ->setOption('method', 'getLongToString')
            ->setOption('criteria', $outgoingPaymentCriteria)
            ->setAttribute('class', 'span4');
        $this->getValidator('outgoing_payment_id')->setOption('criteria', $outgoingPaymentCriteria);



        $this->getWidgetSchema()->moveField('settlement_id', sfWidgetFormSchema::AFTER, 'contract_id');
        $settlement = $this->getObject()->getSettlement();
        if ($settlement) {
            $this->getWidgetSchema()->setDefault('contract_id', $settlement->getContractId());
            $this->getWidgetSchema()->setDefault('creditor_id', $settlement->getContract()->getCreditorId());
        }


        $amountFields = array(
            'paid',
            'balance_reduction'
        );


        foreach ($amountFields as $field) {
            if (!$this->getObject()->isNew()) {
                $this->setWidget($field, new myWidgetFormInputAmount(array('currency_code' => $this->getObject()->getContract()->getCurrencyCode())));
                $this->setValidator($field, new myValidatorNumber());
            } else {
                $this->changeFieldToMyNumberField($field);
            }
        }

        $this->getValidatorSchema()->setPostValidator(new AllocationPostValidator());

    }
}
