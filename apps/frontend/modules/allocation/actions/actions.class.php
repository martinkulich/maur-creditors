<?php

require_once dirname(__FILE__) . '/../lib/allocationGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/allocationGeneratorHelper.class.php';

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

        $notice = 'The item was deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);

        $this->redirect('@allocation');
    }

    public function getFilters()
    {
        $filters = parent::getFilters();
        $changed = false;
        if (isset($filters['outgoing_payment_id']) && !isset($filters['creditor_id'])) {
            $outgoingPayment = OutgoingPaymentPeer::retrieveByPK($filters['outgoing_payment_id']);
            if ($outgoingPayment) {
                $filters['creditor_id'] = $outgoingPayment->getCreditorId();
                $changed = true;
            }
        }

        if (isset($filters['settlement_id']) && (!isset($filters['contract_id']) || !isset($filters['creditor_id']))) {
            $settlement = BaseSettlementPeer::retrieveByPK($filters['settlement_id']);
            if ($settlement) {
                $filters['contract_id'] = $settlement->getContractId();
                $filters['creditor_id'] = $settlement->getContract()->getCreditorId();
                $changed = true;
            }
        }

        if ($changed) {
            $this->setFilters($filters);
        }

        return $filters;
    }
}
