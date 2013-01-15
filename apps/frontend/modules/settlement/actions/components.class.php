<?php

require_once dirname(__FILE__) . '/../lib/settlementGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/settlementGeneratorHelper.class.php';

class settlementComponents extends sfComponents
{

    public function executeSelect(sfWebRequest $request)
    {
        $criteria = new Criteria();
        $criteria->add(SettlementPeer::CONTRACT_ID, $request->getParameter('contract_id'));
        $criteria->add(BaseSettlementPeer::SETTLEMENT_TYPE, SettlementPeer::END_OF_YEAR, Criteria::NOT_EQUAL);
        $criteria->addAscendingOrderByColumn(SettlementPeer::DATE);

        $this->settlementId = $request->getParameter('default', 0);
        $this->widget = new sfWidgetFormPropelChoice(array('model' => 'Settlement', 'criteria' => $criteria, 'add_empty' => true));

    }
}
