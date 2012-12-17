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
//        return sfView::NONE;
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
        $this->executeContractFilter($request, false);
        $this->executeNew($request);

        $this->form = new ClosingSettlementForm($this->settlement);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->processClosingForm($request, $this->form);
        }
    }

    public function executeContractFilter(sfWebRequest $request, $redirect = true)
    {
        $contractId = $request->getParameter('contract_id');
        $contract = ContractPeer::retrieveByPK($contractId);
        if ($contract) {
            $filters = $this->getFilters();
            $filters['contract_id'] = $contractId;
            $filters['creditor_id'] = $contract->getCreditorId();
            $this->setFilters($filters);
        }
        if ($redirect) {
            return $this->redirect('@settlement');
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

    protected function processClosingForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {

            $settlement = $form->save();

            if ($settlement->getSettlementType() == SettlementPeer::CLOSING_BY_REACTIVATION) {
                return $this->forward('payment', 'newReactivation');
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

    public function executePay(sfWebRequest $request)
    {
        $this->settlement = $this->getRoute()->getObject();
        $this->form = new SettlementPayForm($this->settlement);
        
        if($request->isMethod(sfWebRequest::POST))
        {
            $this->processForm($request, $this->form);
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
        $criteria->addSelectColumn(sprintf('sum(%s) as balance_reduction', SettlementPeer::BALANCE_REDUCTION));
        $criteria->addSelectColumn(sprintf('sum(%s) as paid', SettlementPeer::PAID));
        $criteria->addSelectColumn(sprintf('sum(%s) as capitalized', SettlementPeer::CAPITALIZED));
        $criteria->addSelectColumn('CASE sum(coalesce(interest,0) - coalesce(paid,0) - coalesce(capitalized,0)) < 0 WHEN TRUE THEN 0 ELSE sum(coalesce(interest,0) - coalesce(paid,0) - coalesce(capitalized,0))  END  as unsettled');
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
        $filters = $this->getUser()->getAttribute('settlement.filters', $this->configuration->getFilterDefaults(), 'admin_module');
        if (!isset($filters['date']['from'])) {
            $date = new DateTime('now');
            $date->modify('-1 month');
            $filters['date']['from'] = $date->format('Y-m-d');
        }

        if (!isset($filters['date']['to'])) {
            $date = new DateTime('now');
            $date->modify('+1 month');
            $filters['date']['to'] = $date->format('Y-m-d');
        }

        return $filters;
    }

}
