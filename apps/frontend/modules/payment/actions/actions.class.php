<?php

require_once dirname(__FILE__) . '/../lib/paymentGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/paymentGeneratorHelper.class.php';

/**
 * payment actions.
 *
 * @package    rezervuj
 * @subpackage payment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class paymentActions extends autoPaymentActions
{

    public function executeIndex(sfWebRequest $request)
    {
        parent::executeIndex($request);
        $this->sums = $this->getSums();
    }

    protected function getSums()
    {
        $sumPager = $this->getPager();
        $criteria = $sumPager->getCriteria();
        $criteria->clearSelectColumns();
        $criteria->addSelectColumn(sprintf('sum(%s) as amount', PaymentPeer::AMOUNT));
        $criteria->clearOrderByColumns();
        $sumPager->setCriteria($criteria);
        $sumPager->init();

        $statement = RegulationPeer::doSelectStmt($sumPager->getCriteria());
        return $statement->fetch(PDO::FETCH_ASSOC);
        return $sumPager;
    }

    public function executeDelete(sfWebRequest $request)
    {
        $payment = $this->getRoute()->getObject();
        $contract = $payment->getContract();

        $payment->delete();

        $notice = 'The item was deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);

        ServiceContainer::getContractService()->checkContractChanges($contract);

        $this->redirect('@payment');
    }

    public function executeNew(sfWebRequest $request)
    {
        $this->form = $this->configuration->getForm();
        $this->payment = $this->form->getObject();
        $filters = $this->getFilters();

        if (array_key_exists('contract_id', $filters)) {
            $contract = ContractPeer::retrieveByPK($filters['contract_id']);
            if ($contract) {
                $this->payment->setContract($contract);
            }
        }
        $this->form = $this->configuration->getForm($this->payment);
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

        return $this->redirect('@payment');
    }
}
