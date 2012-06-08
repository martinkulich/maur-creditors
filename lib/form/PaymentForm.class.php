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

        $this->setWidget('date', new myJQueryDateWidget());
        $this->setValidator('date', new myValidatorDate());


        $this->getWidget('contract_id')->setOption('add_empty', true);

        $this->setWidget('creditor_id', new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'order_by' => array('Lastname', 'asc'), 'add_empty' => true)));
        $this->setValidator('creditor_id', new sfValidatorPropelChoice(array('model' => 'Creditor', 'required' => true)));
        $this->getWidgetSchema()->moveField('creditor_id', sfWidgetFormSchema::FIRST);
        $contract = ContractPeer::retrieveByPK($this->getObject()->getContractId());
        if ($contract) {
            $this->getWidgetSchema()->setDefault('creditor_id', $contract->getCreditorId());
        }
        $this->getWidget('creditor_id')->setAttribute('onchange', sprintf("updateSelectBox('%s','%s','%s', '%s'); ;", url_for('@update_contract_select?form_name=payment'), 'payment_creditor_id', 'payment_contract_id', 'creditor_id'));
    }

    public function doSave($con = null)
    {
        parent::doSave($con);
        $this->getObject()->reload();
        $contractService = ServiceContainer::getContractService();
        $contract = $this->getObject()->getContract();
        $contractService->checkContractActivation($contract, $con);
    }
}
