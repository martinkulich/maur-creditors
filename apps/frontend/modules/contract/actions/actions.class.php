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

    public function executePrintList(sfWebRequest $request)
    {
        $this->pager = $this->getPager();
        $this->sort = $this->getSort();
        ServiceContainer::getPdfService()->generatePdf('contractList.pdf', 'contract', 'printList', array('pager' => $this->pager, 'sort' => $this->sort, 'helper' => $this->helper));
    }

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
        $this->contract->setClosedAt('now');
        $this->form = new ContractCloseForm($this->contract);

        if ($request->isMethod('post')) {
            $this->processForm($request, $this->form);
            ServiceContainer::getMessageService()->addFromErrors($this->form->getEmbeddedForm('closing_settlement'), true);
        }
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully' : 'The item was updated successfully';
            ServiceContainer::getMessageService()->addSuccess($notice);

            $contract = $form->save();
            $contract->reload();

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $contract)));

            $redirect = array('sf_route' => 'contract_edit', 'sf_subject' => $contract);

            if ($request->getParameter('save_and_reuse', false)) {
                $request->setParameter('contract_id', $contract->getId());

                return $this->forward('payment', 'newReactivation');
            }


            return $this->redirect($redirect, 205);
        } else {
            ServiceContainer::getMessageService()->addFromErrors($form);
        }
    }

    public function executeClosingAmount(sfWebRequest $request)
    {
        $closingAmount = 0;
        $contract = ContractPeer::retrieveByPK($request->getParameter('id'));
        $date = new DateTime($request->getParameter('date'));

        if ($contract) {
            $closingAmount = ServiceContainer::getContractService()->getContractClosingAmount($contract, $date);
        }
        $result = array(
            'unsettled' => $closingAmount,
        );

        $this->data = json_encode($result);
    }
}
