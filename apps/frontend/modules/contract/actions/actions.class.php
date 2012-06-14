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

    public function executeUpdateSelect(sfWebRequest $request)
    {
        return $this->renderComponent('contract', 'select', array('creditor_id' => $request->getParameter('creditor_id'), 'formName' => $request->getParameter('form_name')));
    }

    public function executeRequlation(sfWebRequest $request)
    {

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

    public function executeClose(sfWebRequest $request)
    {
        $this->contract = $this->getRoute()->getObject();
        $this->form = new ContractCloseForm($this->contract);

        if ($request->isMethod('post')) {
            $this->processForm($request, $this->form);
        }
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully' : 'The item was updated successfully';

            $contract = $form->save();

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $contract)));

            $redirect = array('sf_route' => 'contract_edit', 'sf_subject' => $contract);

            if ($request->hasParameter('_save_and_add')) {
                $notice .=' You can add another one below.';
                $redirect = '@contract_new';
            }

            ServiceContainer::getMessageService()->addSuccess($notice);

            return $this->redirect($redirect, 205);
        } else {
            ServiceContainer::getMessageService()->addFromErrors($form, true);
            ServiceContainer::getMessageService()->addFromErrors($form->getEmbeddedForm('closing_settlement'), true);
            foreach($form->getErrorSchema()->getErrors() as $key=>$error)
            {
                var_dump($key, $error->getMessage());
            }

        }
    }

    public function executeUnsettledAmount(sfWebRequest $request)
    {
        $unsettled = 0;
        $contract = ContractPeer::retrieveByPK($request->getParameter('id'));
        $date = $request->getParameter('date');

        if ($contract) {
            $contractService = ServiceContainer::getContractService();
            $unsettled = $contract->getUnsettled(new DateTime($date));
            $settlement = new Settlement();
            $settlement->setContract($contract);
            $settlement->setDate($date);
            $settlement->setBalance($contractService->getBalanceForSettlement($settlement));

            $interest = $contractService->getInterestForSettlement($settlement);
            $settlement->setInterest($interest);

            $unsettled += $settlement->getUnsettled();
        }
        $result = array(
            'unsettled' => $unsettled,
        );

        $this->data = json_encode($result);
    }
}
