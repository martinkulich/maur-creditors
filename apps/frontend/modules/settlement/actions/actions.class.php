<?php

require_once dirname(__FILE__) . '/../lib/settlementGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/settlementGeneratorHelper.class.php';

/**
 * settlement actions.
 *
 * @package    rezervuj
 * @subpackage settlement
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class settlementActions extends autoSettlementActions
{

    public function executeUpdateSelect(sfWebRequest $request)
    {
        return $this->renderComponent('settlement', 'select', array('contractId' => $request->getParameter('contract_id'), 'formName' => $request->getParameter('form_name')));
    }


    public function executeNote(sfWebRequest $request)
    {
        $this->settlement = $this->getRoute()->getObject();
    }

    public function executeCheckContracts(sfWebRequest $request)
    {
        $contractService = ServiceContainer::getContractService();
        foreach (ContractPeer::doSelect(new Criteria()) as $contract) {
            $contractService->checkContractChanges($contract);
        }
        return sfView::NONE;
        return $this->redirect('@settlement');
    }

    public function executeIndex(sfWebRequest $request)
    {
//        $this->executeCheckContracts();
        parent::executeIndex($request);
        $this->sums = $this->getSums();
    }

    public function executeNew(sfWebRequest $request)
    {
        $this->form = $this->configuration->getForm();
        $this->settlement = $this->form->getObject();
        $this->settlement->setSettlementType(SettlementPeer::MANUAL);
        $filters = $this->getFilters();

        if (array_key_exists('contract_id', $filters)) {
            $contract = ContractPeer::retrieveByPK($filters['contract_id']);
            if ($contract) {
                $contractService = ServiceContainer::getContractService();
                $this->settlement->setContract($contract);
                $this->settlement->setDate('now');
                $this->settlement->setBalance($contractService->getBalanceForSettlement($this->settlement));
                $this->settlement->setInterest($contractService->getInterestForSettlement($this->settlement));
            }
        }
        $this->form = $this->configuration->getForm($this->settlement);
    }

    public function executeCreate(sfWebRequest $request)
    {
        $this->executeNew($request);
        $this->setTemplate('new');

        $this->processForm($request, $this->form);
    }

    public function executeClose(sfWebRequest $request)
    {
        $this->executeNew($request);

        $this->form = new ClosingSettlementForm($this->settlement);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->processClosingForm($request, $this->form);
        }
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully' : 'The item was updated successfully';

            $settlement = $form->save();

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $settlement)));

//            $redirect = array('sf_route' => 'settlement_edit', 'sf_subject' => $settlement);
            $redirect = '@settlement';

            ServiceContainer::getMessageService()->addSuccess($notice);

            return $this->redirect($redirect, 205);
        } else {
            ServiceContainer::getMessageService()->addFromErrors($form);
        }
    }

    protected function processAllocationForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully' : 'The item was updated successfully';

            $allocation = $form->save();

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $allocation)));

//            $redirect = array('sf_route' => 'settlement_edit', 'sf_subject' => $settlement);
            $redirect = '@allocation';

            ServiceContainer::getMessageService()->addSuccess($notice);

            return $this->redirect($redirect, 205);
        } else {
            ServiceContainer::getMessageService()->addFromErrors($form);
        }
    }

    protected function processNewOutgoingPaymentForm(sfWebRequest $request, sfForm $form, settlement $settlement)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully' : 'The item was updated successfully';

            $outgoingPayment = $form->save();

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $outgoingPayment)));

//            $redirect = array('sf_route' => 'settlement_edit', 'sf_subject' => $settlement);
            $redirect = '@settlement_allocate?id=' . $settlement->getId();

            ServiceContainer::getMessageService()->addSuccess($notice);

            return $this->redirect($redirect);
        } else {
            ServiceContainer::getMessageService()->addFromErrors($form);
        }
    }

    protected function processClosingForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {

            $settlement = $form->save();

            if ($settlement->getSettlementType() == SettlementPeer::CLOSING_BY_REACTIVATION) {
                $date = new DateTime($settlement->getDate());
                $date->modify('+1 day');
                return $this->redirect('@payment_newReactivation?amount=' . $settlement->getBalanceAfterSettlement() . '&date=' . $date->format('Y-m-d'));
            }

            ServiceContainer::getMessageService()->addSuccess('Contract closed successfully');

            return $this->redirect('@contract', 205);
        } else {
            ServiceContainer::getMessageService()->addFromErrors($form, true);
        }
    }

    public function executeDelete(sfWebRequest $request)
    {
        $settlement = $this->getRoute()->getObject();
        $contract = $settlement->getContract();

        if (in_array($settlement->getSettlementType(), array(
            SettlementPeer::CLOSING,
            SettlementPeer::CLOSING_BY_REACTIVATION,
        ))
        ) {
            $contract->setClosedAt(null);
            $contract->save();
        }

        $settlement->delete();
        $notice = 'The item was deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);

        ServiceContainer::getContractService()->checkContractChanges($contract);

        return $this->redirect('@settlement');
    }

    public function executeCapitalize(sfWebRequest $request)
    {
        $settlement = $this->getRoute()->getObject();
        $settlement->setCapitalized($settlement->getUnsettled() + $settlement->getCapitalized());
        $settlement->save();
        ServiceContainer::getContractService()->checkContractChanges($settlement->getContract());

        return $this->redirect('@settlement');
    }

    public function executeAllocate(sfWebRequest $request)
    {
        $this->settlement = $this->getRoute()->getObject();
        $this->forward404Unless($this->settlement);
        $outGoingPaymentCount = OutgoingPaymentPeer::doCount(ServiceContainer::getAllocationService()->getAllocableOutgoingPaymentsCriteria($this->getRoute()->getObject()->getContract()->getCreditor()));
        if ($outGoingPaymentCount) {
            $this->allocation = new Allocation();
            $this->allocation->setSettlement($this->settlement);
            $this->form = new SettlementAllocateForm($this->allocation);

            if ($request->isMethod(sfWebRequest::POST)) {
                $this->processAllocationForm($request, $this->form);
            }
        } else {
            $warning = ServiceContainer::getTranslateService()->__('There is no outgoing payment to be used for allocation');
            ServiceContainer::getMessageService()->addWarning($warning);

            $redirect = '@settlement_newOutgoingPayment?id=' . $this->settlement->getId();
            return $this->redirect($redirect);
        }


    }

    public function executeNewOutgoingPayment(sfWebRequest $request)
    {
        $this->settlement = $this->getRoute()->getObject();
        $this->forward404Unless($this->settlement);

        $this->outgoingPayment = new OutgoingPayment();
        $this->outgoingPayment->setCreditor($this->settlement->getContract()->getCreditor());
        $this->outgoingPayment->setCurrency($this->settlement->getContract()->getCurrency());
        $this->form = new settlementNewOutgoingPaymentForm($this->outgoingPayment);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->processNewOutgoingPaymentForm($request, $this->form, $this->settlement);
        }
    }


    public function executeInterest(sfWebRequest $request)
    {
        $this->calculate($request, 'interest');
        $this->setTemplate('calculate');
    }

    public function executeBalance(sfWebRequest $request)
    {
        $this->calculate($request, 'balance');
        $this->setTemplate('calculate');
    }

    public function calculate(sfWebRequest $request, $what = 'interest')
    {
        $this->data = null;

        $contract = ContractPeer::retrieveByPK($request->getParameter('contract_id'));

        if ($contract) {
            $calculate = false;
            $date = new Datetime($request->getParameter('date'));
            $requestHasDate = $request->hasParameter('date');
            $contractService = ServiceContainer::getContractService();
            $settlement = SettlementPeer::retrieveByPK($request->getParameter('settlement_id'));
            if (!$settlement) {
                $settlement = $contract->getSettlementForDate($date);
                if ($settlement) {
                    $settlement->setManualBalance(false);
                    $settlement->setManualInterest(false);
                    $calculate = true;
                }
            }
            if (!$settlement) {
                $settlement = new Settlement();
                $settlement->setDate($date);
                $settlement->setContract($contract);
                $settlementDate = $date;
            } else {
                $settlementDate = new DateTime($settlement->getDate());
                if (!$requestHasDate) {
                    $date = $settlementDate;
                }
            }

            $format = 'Y-m-d';
            if (!$calculate) {
                $calculate = $settlementDate->format($format) != $date->format($format) || $settlement->isNew();
            }
            if ($calculate) {
                $settlement->setDate($date);
                $settlementType = $request->getParameter('settlement_type');
                if (SettlementPeer::settlementTypeExists($settlementType)) {
                    $settlement->setSettlementType($settlementType);
                }
            }
            if ($what == 'interest') {
                if ($calculate) {
                    $amount = $contractService->getInterestForSettlement($settlement);
                } else {
                    $amount = $settlement->getInterest();
                }
            } else {
                if ($calculate) {
                    $amount = $contractService->getBalanceForSettlement($settlement);
                } else {
                    $amount = $settlement->getBalance();
                }
            }


            $result = array(
                'amount' => round($amount, 2),
                'calculate' => $calculate,
            );

            $this->data = json_encode($result);
        }
    }

    protected function getPager()
    {
        $pager = $this->configuration->getPager('settlement');
        $criteria = $this->buildCriteria();
        $pager->setCriteria($criteria);
        $pager->setPage($this->getPage());
        $pager->setPeerMethod($this->configuration->getPeerMethod());
        $pager->setPeerCountMethod($this->configuration->getPeerCountMethod());
        $pager->init();
        return $pager;
    }

    protected function getSums()
    {
        $sumPager = $this->getPager();
        $criteria = $sumPager->getCriteria();
        $criteria->clearSelectColumns();
        $criteria->addJoin(SettlementPeer::CONTRACT_ID, ContractPeer::ID);
        $criteria->addSelectColumn(ContractPeer::CURRENCY_CODE);
        $criteria->addSelectColumn(sprintf('sum(%s) as interest', SettlementPeer::INTEREST));
        $criteria->addSelectColumn(sprintf('sum(%s) as balance', SettlementPeer::BALANCE));
        $criteria->addSelectColumn(sprintf('sum(settlement_balance_reduction(%s)) as balance_reduction', SettlementPeer::ID));
        $criteria->addSelectColumn(sprintf('sum(settlement_paid(%s)) as paid', SettlementPeer::ID));
        $criteria->addSelectColumn(sprintf('sum(%s) as capitalized', SettlementPeer::CAPITALIZED));
        $criteria->addSelectColumn(sprintf(
            'CASE
                sum(%s) - sum(settlement_paid(%s)) - sum(%s) < 0
             WHEN TRUE THEN 0
             ELSE sum(%s) - sum(settlement_paid(%s)) - sum(%s)
             END  as unsettled',
            SettlementPeer::INTEREST,
            SettlementPeer::ID,
            SettlementPeer::CAPITALIZED,
            SettlementPeer::INTEREST,
            SettlementPeer::ID,
            SettlementPeer::CAPITALIZED
        ));
        $criteria->clearOrderByColumns();
        $criteria->addGroupByColumn(ContractPeer::CURRENCY_CODE);
        $sumPager->setCriteria($criteria);
        $sumPager->init();

        $statement = SettlementPeer::doSelectStmt($sumPager->getCriteria());

        $sums = array();
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $sums[$row['currency_code']] = $row;
        }
        ksort($sums);
        return $sums;
    }

    protected function getFilters()
    {
        $changed = false;
        $filters = $this->getUser()->getAttribute('settlement.filters', $this->configuration->getFilterDefaults(), 'admin_module');

        $contract = null;
        if (isset($filters['contract_id']) && !isset($filters['creditor_id'])) {
            $contract = ContractPeer::retrieveByPK($filters['contract_id']);
            if ($contract) {
                $filters['creditor_id'] = $contract->getCreditorId();
                $changed = true;
            }
        }

        if (!isset($filters['date']['from'])) {
            if ($contract && $contract->getActivatedAt()) {
                $date = new DateTime($contract->getActivatedAt());
            } else {
                $date = new DateTime('now');
                $date->modify('-1 month');
            }
            $filters['date']['from'] = $date->format('Y-m-d');
            $changed = true;
        }

        if (!isset($filters['date']['to'])) {
            if ($contract && $contract->getLastSettlement()) {
                $date = new DateTime($contract->getLastSettlement()->getDate());
            } else {
                $date = new DateTime('now');
                $date->modify('+1 month');
            }
            $filters['date']['to'] = $date->format('Y-m-d');
            $changed = true;
        }


        if ($changed) {
            $this->setFilters($filters);
        }
        return $filters;
    }

}
