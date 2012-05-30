<?php

require_once dirname(__FILE__) . '/../lib/contractGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/contractGeneratorHelper.class.php';

class contractComponents extends sfComponents
{
    public function executeSelect(sfWebRequest $request)
    {
        $criteria = new Criteria();
        $criteria->add(ContractPeer::CREDITOR_ID, $request->getParameter('creditor_id'));
        $this->widget = new sfWidgetFormPropelChoice(array('model' => 'Contract', 'criteria' => $criteria));
    }
}
