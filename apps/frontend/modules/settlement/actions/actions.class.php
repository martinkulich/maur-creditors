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

    public function executeIndex(sfWebRequest $request)
    {
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
        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeContractFilter(sfWebRequest $request)
    {
        $filters = $this->getFilters();
        $filters['contract_id'] = $request->getParameter('contract_id');
        $this->setFilters($filters);

        return $this->redirect('@settlement');
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully' : 'The item was updated successfully';

            $settlement = $form->save();

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $settlement)));

            $redirect = array('sf_route' => 'settlement_edit', 'sf_subject' => $settlement);


            ServiceContainer::getMessageService()->addSuccess($notice);

            return $this->redirect($redirect, 205);
        } else {
            ServiceContainer::getMessageService()->addFromErrors($form);
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
        $settlement = $this->getRoute()->getObject();
        $settlement->setPaid($settlement->getUnsettled() + $settlement->getPaid());
        $settlement->save();
        ServiceContainer::getContractService()->checkContractChanges($settlement->getContract());

        return $this->redirect('@settlement');
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
            $date = new Datetime($request->getParameter('date'));

            $contractService = ServiceContainer::getContractService();
            $settlement = SettlementPeer::retrieveByPK($request->getParameter('settlement_id'));
            if (!$settlement) {
                $settlement = new Settlement();
                $settlement->setDate($date);
                $settlementDate = $date;
            }else
            {
                $settlementDate = new DateTime($settlement->getDate());
            }
            $settlement->setContract($contract);

            $format = 'Y-m-d';
            $calculate = $settlementDate->format($format) != $date->format($format) || $settlement->isNew();
            if($calculate)
            {
                $settlement->setDate($date);
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
                'calculate'=>$calculate,
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
        $criteria->addSelectColumn(sprintf('sum(%s) as interest', SettlementPeer::INTEREST));
        $criteria->addSelectColumn(sprintf('sum(%s) as balance', SettlementPeer::BALANCE));
        $criteria->addSelectColumn(sprintf('sum(%s) as balance_reduction', SettlementPeer::BALANCE_REDUCTION));
        $criteria->addSelectColumn(sprintf('sum(%s) as paid', SettlementPeer::PAID));
        $criteria->addSelectColumn(sprintf('sum(%s) as capitalized', SettlementPeer::CAPITALIZED));
        $criteria->addSelectColumn('CASE sum(coalesce(interest,0) - coalesce(paid,0) - coalesce(capitalized,0)) < 0 WHEN TRUE THEN 0 ELSE sum(coalesce(interest,0) - coalesce(paid,0) - coalesce(capitalized,0))  END  as unsettled');
        $criteria->clearOrderByColumns();
        $sumPager->setCriteria($criteria);
        $sumPager->init();

        $statement = SettlementPeer::doSelectStmt($sumPager->getCriteria());
        return $statement->fetch(PDO::FETCH_ASSOC);
        return $sumPager;
    }
}
