<?php

require_once dirname(__FILE__).'/../lib/outgoingPaymentGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/outgoingPaymentGeneratorHelper.class.php';

/**
 * outgoingPayment actions.
 *
 * @package    rezervuj
 * @subpackage outgoingPayment
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class outgoingPaymentActions extends autoOutgoingPaymentActions
{
    public function executeNote(sfWebRequest $request)
    {
        $this->outgoinPayment = $this->getRoute()->getObject();
    }
}
