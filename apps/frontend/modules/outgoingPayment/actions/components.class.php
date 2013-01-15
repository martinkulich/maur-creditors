<?php

require_once dirname(__FILE__) . '/../lib/outgoingPaymentGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/outgoingPaymentGeneratorHelper.class.php';

class outgoingPaymentComponents extends sfComponents
{

    public function executeSelect(sfWebRequest $request)
    {
        $criteria = new Criteria();
        $criteria->add(OutgoingPaymentPeer::CREDITOR_ID, $request->getParameter('creditor_id'));

        $this->outgoingPaymentId = $request->getParameter('default', 0);
        $this->widget = new sfWidgetFormPropelChoice(array('model' => 'OutgoingPayment', 'criteria' => $criteria, 'add_empty' => true));

    }
}
