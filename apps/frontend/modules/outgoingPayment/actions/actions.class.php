<?php

require_once dirname(__FILE__) . '/../lib/outgoingPaymentGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/outgoingPaymentGeneratorHelper.class.php';

/**
 * outgoingPayment actions.
 *
 * @package    rezervuj
 * @subpackage outgoingPayment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class outgoingPaymentActions extends autoOutgoingPaymentActions
{
    public function executeUpdateSelect(sfWebRequest $request)
    {
        return $this->renderComponent('outgoingPayment', 'select', array('contractId' => $request->getParameter('contract_id'), 'formName' => $request->getParameter('form_name')));
    }

    public function executeNote(sfWebRequest $request)
    {
        $this->outgoinPayment = $this->getRoute()->getObject();
    }

    public function executeDetail(sfWebRequest $request)
    {
        $this->outgoinPayment = $this->getRoute()->getObject();
    }

    public function executeDelete(sfWebRequest $request)
    {
        $outgoingPayment = $this->getRoute()->getObject();
        foreach ($outgoingPayment->getAllocations() as $allocation) {

            $contract = $allocation->getSettlement()->getContract();
            $allocation->delete();
            ServiceContainer::getContractService()->checkContractChanges($contract);
        }

        $outgoingPayment->delete();

        $notice = 'The item was deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);

        $this->redirect('@allocation');
    }

}
