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
}
