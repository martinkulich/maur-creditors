<?php

require_once dirname(__FILE__) . '/../lib/contractGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/contractGeneratorHelper.class.php';

/**
 * contract actions.
 *
 * @package    rezervuj
 * @subpackage contract
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class contractActions extends autoContractActions
{

    public function executeExcludeFromReport(sfWebRequest $request)
    {
        $contract = $this->getRoute()->getObject();
        $report = ReportPeer::retrieveByPK($request->getParameter('report_type'));
        $this->forward404Unless($contract && $report);

        $criteria = new Criteria();
        $criteria
            ->add(ContractExcludedReportPeer::CONTRACT_ID, $contract->getId())
            ->add(ContractExcludedReportPeer::REPORT_CODE, $report->getCode());

        if(!ContractExcludedReportPeer::doCount($criteria))
        {
            $contractExcludedReport = new ContractExcludedReport();
            $contractExcludedReport->setContract($contract);
            $contractExcludedReport->setReport($report);
            $contractExcludedReport->save();
        }
        $referef = $request->getReferer();
        $redirect = $referef ? $referef : '@report?report_type='.$report->getCode();

        return $this->redirect($redirect);

    }

    public function executeNote(sfWebRequest $request)
    {
        $this->contract = $this->getRoute()->getObject();
    }


    public function executeUpdateSelect(sfWebRequest $request)
    {
        return $this->renderComponent('contract', 'select', array('creditor_id' => $request->getParameter('creditor_id'), 'formName' => $request->getParameter('form_name')));
    }

    public function executeRequlation(sfWebRequest $request)
    {

    }

    public function executeIndex(sfWebRequest $request)
    {
        parent::executeIndex($request);
        $this->currency = ServiceContainer::getCurrencyService()->getDefaultCurrency();
    }

    public function executeCopy(sfWebRequest $request)
    {
        $this->contract = $this->getRoute()->getObject();
        $this->form = new ContractCopyForm($this->contract);
    }

    public function executeDuplicate(sfWebRequest $request)
    {
        $this->executeCopy($request);
        $this->processForm($request, $this->form);

        $this->setTemplate('copy');
    }

    public function executeClosingAmount(sfWebRequest $request)
    {
        $closingAmount = array(
            'unsettled' => 0,
            'balance_reduction' => 0,
        );
        $contractService = ServiceContainer::getContractService();
        $contract = ContractPeer::retrieveByPK($request->getParameter('id'));
        $date = new DateTime($request->getParameter('date'));

        if ($contract) {
            $settlementType = $request->getParameter('settlement_type');

            if (!SettlementPeer::settlementTypeExists($settlementType)) {
                $settlementType = null;
            }

            $closingAmount = ServiceContainer::getContractService()->getContractClosingAmount($contract, $date, $settlementType);
        }

        $this->data = json_encode($closingAmount);
    }

    public function executePaidDetail(sfWebRequest $request)
    {
        $this->contract = $this->getRoute()->getObject();
        $this->forward404Unless($this->contract);

        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(OutgoingPaymentPeer::DATE);
        $criteria->addJoin(OutgoingPaymentPeer::ID, AllocationPeer::OUTGOING_PAYMENT_ID);
        $criteria->addJoin(AllocationPeer::SETTLEMENT_ID, SettlementPeer::ID);
        $criteria->add(SettlementPeer::CONTRACT_ID, $this->contract->getId());

        $filters = $request->getParameter('filter');

        if (is_array($filters) && array_key_exists('year', $filters)) {

            $monthFrom = array_key_exists('month', $filters) ? $filters['month'] : '01';
            $monthTo = array_key_exists('month', $filters) ? $filters['month'] : '12';
            $firstDay = new DateTime(sprintf('%s-%s-01', $filters['year'], $monthFrom));
            $lastDay = new DateTime(sprintf('%s-%s-01', $filters['year'], $monthTo));
            $lastDay->modify('last day of this month');

            $criterion1 = $criteria->getNewCriterion(OutgoingPaymentPeer::DATE, $firstDay, Criteria::GREATER_EQUAL);
            $criterion2 = $criteria->getNewCriterion(OutgoingPaymentPeer::DATE, $lastDay, Criteria::LESS_EQUAL);
            $criterion1->addAnd($criterion2);
            $criteria->addAnd($criterion1);
        }

        $this->outgoingPayments = OutgoingPaymentPeer::doSelect($criteria);
    }

}
