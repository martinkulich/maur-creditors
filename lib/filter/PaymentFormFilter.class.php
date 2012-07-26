<?php

/**
 * Payment filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class PaymentFormFilter extends BasePaymentFormFilter
{

    public function configure()
    {
        sfProjectConfiguration::getActive()->loadHelpers('Url');

        $this->setWidget('date', new MyJQueryFormFilterDate());

        $this->setValidator('date', new MyValidatorDateRange(array('required' => false)));

        $this->unsetField('amount');

        $this->setWidget('creditor_id', new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'order_by' => array('Lastname', 'asc'), 'add_empty'=>true)));
        $this->setValidator('creditor_id', new sfValidatorPropelChoice(array('model' => 'Creditor', 'required' => false)));
        $this->getWidgetSchema()->moveField('creditor_id', sfWidgetFormSchema::FIRST);
        $contract = ContractPeer::retrieveByPK($this->getValue('contract_id'));
        if($contract)
        {
            $this->getWidgetSchema()->setDefault('creditor_id', $contract->getCreditorId());
        }
        $this->getWidget('creditor_id')->setAttribute('onchange', sprintf("updateSelectBox('%s','%s','%s', '%s'); ;", url_for('@update_contract_select?form_name=payment_filters'), 'payment_filters_creditor_id', 'payment_filters_contract_id', 'creditor_id'));

        $paymemtTypeChoices = ServiceContainer::getPaymentService()->getPaymentTypeChoices();
        $this->setWidget('payment_type', new sfWidgetFormChoice(array('choices'=>$paymemtTypeChoices)));
        $this->setValidator('payment_type', new sfValidatorChoice(array('choices'=>  array_keys($paymemtTypeChoices), 'required'=>false)));
    }

    public function addCreditorIdColumnCriteria(Criteria $criteria, $field, $value)
    {
        $criteria->addJoin(PaymentPeer::CONTRACT_ID, ContractPeer::ID);
        $criteria->add(ContractPeer::CREDITOR_ID, $value);
        return $criteria;
    }

    public function addPaymentTypeColumnCriteria(Criteria $criteria, $field, $value)
    {
        $criteria->add(PaymentPeer::PAYMENT_TYPE, $value);
        return $criteria;
    }

    public function renderJavascript($attributes = array())
    {
        die(var_dump('xxx'));
        parent::render($attributes);
    }
}
