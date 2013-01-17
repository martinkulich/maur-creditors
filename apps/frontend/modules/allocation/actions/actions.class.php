<?php

require_once dirname(__FILE__).'/../lib/allocationGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/allocationGeneratorHelper.class.php';

/**
 * allocation actions.
 *
 * @package    rezervuj
 * @subpackage allocation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class allocationActions extends autoAllocationActions
{

    public function executeDelete(sfWebRequest $request)
    {
        $allocation = $this->getRoute()->getObject();
        $contract = $allocation->getSettlement()->getContract();

        $allocation->delete();

        ServiceContainer::getContractService()->checkContractChanges($contract);

        $notice='The item was deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);

        $this->redirect('@allocation');
    }

    public function executeFilters(sfWebRequest $request)
    {

        $filters = $this->getFilters();
        if(isset($filters['outgoing_payment_id']) && !isset($filters['creditor_id']))
        {
            $outgoingPayment = OutgoingPaymentPeer::retrieveByPK($filters['outgoing_payment_id']);
            if($outgoingPayment)
            {
                $filters['creditor_id'] = $outgoingPayment->getCreditorId();
            }
        }

        if(isset($filters['settlement_id']) && (!isset($filters['contract_id']) || !isset($filters['creditor_id'])))
        {
            $settlement = BaseSettlementPeer::retrieveByPK($filters['settlement_id']);
            if($settlement)
            {
                $filters['contract_id'] = $settlement->getContractId();
                $filters['creditor_id'] = $settlement->getContract()->getCreditorId();
            }
        }
        $this->setFilters($filters);

        $this->form = $this->configuration->getFilterForm($this->getFilters());

        $this->form->bind($this->getFilters());

    }

    public function executeFilter(sfWebRequest $request)
    {

        $this->form = $this->configuration->getFilterForm($this->getFilters());

        $this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid())
        {
            $this->setFilters($this->form->getValues());
            $reset = $request->getParameter('reset', false);
            $code = $reset ? 302 : 205;
            return $this->redirect('@allocation', $code);
        }
        else
        {
            ServiceContainer::getMessageService()->addFromErrors($this->form);
        }
        $this->pager = $this->getPager();
        $this->sort = $this->getSort();

        $this->setTemplate('filters');
    }

}
