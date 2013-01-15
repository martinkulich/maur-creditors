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
        $outgingPayment = $this->getRoute()->getObject();
        $this->forward404Unless($outgingPayment);

        $settlements = $outgingPayment->getSettlements();
        if (count($settlements) > 0) {
            $dateFrom = null;
            $dateTo = null;
            foreach ($settlements as $settlement) {
                if ($dateFrom == null || $dateFrom > $settlement->getDate()) {
                    $dateFrom = $settlement->getDate();
                }

                if ($dateTo == null || $dateTo < $settlement->getDate()) {
                    $dateTo = $settlement->getDate();
                }
            }
            ServiceContainer::getMessageService()->addError('There are some settlements related to this outgoing payment, pls unrelate them before deletion');
            $filters = array(
                'outgoing_payment_id' => $outgingPayment->getId(),
                'date' => array(
                    'from' => $dateFrom,
                    'to' => $dateTo,
                ),
            );
            $this->getUser()->setAttribute('settlement.filters', $filters, 'admin_module');

            return $this->redirect('@settlement');
        } else {
            parent::executeDelete($request);
        }
    }

}
