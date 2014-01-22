<?php

/**
 * Payment form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class PaymentForm extends BasePaymentForm
{

    public function configure()
    {
        sfProjectConfiguration::getActive()->loadHelpers('Url');

        $this->changeFieldToMyNumberField('amount');
        
        $this->setWidget('date', new myJQueryDateWidget());
        $this->setValidator('date', new myValidatorDate());



        $this->setWidget('creditor_id', new sfWidgetFormPropelChoice(array('model' => 'Subject', 'order_by' => array('Lastname', 'asc'), 'add_empty' => true)));
        $this->setValidator('creditor_id', new sfValidatorPropelChoice(array('model' => 'Subject', 'required' => true)));
        $this->getWidgetSchema()->moveField('creditor_id', sfWidgetFormSchema::FIRST);
        $contract = ContractPeer::retrieveByPK($this->getObject()->getContractId());
        if ($contract) {
            $this->getWidget('creditor_id')->setDefault($contract->getCreditorId());
            $this->getWidgetSchema()->setDefault('sender_bank_account', $contract->getCreditor()->getBankAccount());
        }

        $this->getWidget('contract_id')->setOption('add_empty', true);
        $this->getWidget('creditor_id')->setAttribute('onchange', sprintf("updateSelectBox('%s','%s','%s', '%s', 'with_inactive');", url_for('@update_contract_select?form_name=payment'), 'payment_creditor_id', 'payment_contract_id', 'creditor_id', true));

        $paymemtTypeChoices = ServiceContainer::getPaymentService()->getPaymentTypeChoices(false);
        $this->setWidget('payment_type', new sfWidgetFormChoice(array('choices' => $paymemtTypeChoices)));
        $this->setValidator('payment_type', new sfValidatorChoice(array('choices' => array_keys($paymemtTypeChoices))));
        $this->getWidgetSchema()->moveField('payment_type', sfWidgetFormSchema::FIRST);
        $this->getWidget('bank_account_id')->setOption('order_by', array('Name', 'desc'));

        if (!$this->getObject()->isNew()) {
            $this->unsetField('date');
            $this->unsetField('contract_id');
            $this->unsetField('creditor_id');
        }
    }

    public function doSave($con = null)
    {
        parent::doSave($con);
        $this->getObject()->reload();
        $contractService = ServiceContainer::getContractService();
        $contract = $this->getObject()->getContract();
        $contractService->checkContractChanges($contract);
    }
}
