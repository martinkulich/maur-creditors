<?php

require_once dirname(__FILE__) . '/../lib/outgoingPaymentGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/outgoingPaymentGeneratorHelper.class.php';

class outgoingPaymentComponents extends sfComponents
{

    public function executeSelect(sfWebRequest $request)
    {
        $criteria = new Criteria();
        $criteria->add(OutgoingPaymentPeer::CREDITOR_ID, $request->getParameter('creditor_id'));

        $this->outgoingPaymentId = (integer) $request->getParameter('default', 0);
        $formClass = sfInflector::humanize($this->formName . 'Form');
        if (class_exists($formClass)) {
            if ($this->formName == 'allocation') {
                $creditor = SubjectPeer::retrieveByPK($request->getParameter('creditor_id'));
                $outgoingPayment = OutgoingPaymentPeer::retrieveByPK($this->outgoingPaymentId);
                $criteria = ServiceContainer::getAllocationService()->getAllocableOutgoingPaymentsCriteria($creditor, $outgoingPayment);

            }
            $form = new $formClass;


            $this->widget = $form->getWidget('outgoing_payment_id');
        } else {
            $this->widget = new sfWidgetFormPropelChoice(array('model' => 'OutgoingPayment', 'criteria' => $criteria, 'add_empty' => true));
        }

        $this->widget->setOption('criteria', $criteria);

    }
}
