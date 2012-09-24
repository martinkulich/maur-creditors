<?php

require_once dirname(__FILE__) . '/../lib/contractGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/contractGeneratorHelper.class.php';

class contractComponents extends sfComponents
{

    public function executeSelect(sfWebRequest $request)
    {
        $criteria = new Criteria();
        $criteria->add(ContractPeer::CREDITOR_ID, $request->getParameter('creditor_id'));
        $filter = $request->getParameter('filter');
        if (!in_array($filter, array('with_inactive', 'all'))) {
            $criteria->add(ContractPeer::ACTIVATED_AT, null, Criteria::ISNOTNULL);
        }

        if ($filter != 'all') {
//            $criteria->add(ContractPeer::CLOSED_AT, null, Criteria::ISNULL);
        }
        $this->contractId = $request->getParameter('default', 0);
        $this->widget = new sfWidgetFormPropelChoice(array('model' => 'Contract', 'criteria' => $criteria, 'add_empty' => true));
    }
}
