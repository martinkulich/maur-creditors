<?php

require_once dirname(__FILE__) . '/../lib/creditorGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/creditorGeneratorHelper.class.php';

/**
 * creditor actions.
 *
 * @package    rezervuj
 * @subpackage creditor
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class creditorActions extends autoCreditorActions
{

    public function executePaidDetail(sfWebRequest $request)
    {
        $this->creditor = $this->getRoute()->getObject();
        $this->forward404Unless($this->creditor);

        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(OutgoingPaymentPeer::DATE);
        $criteria->add(OutgoingPaymentPeer::CREDITOR_ID, $this->creditor->getId());

        $filters = $request->getParameter('filter');

        if (is_array($filters) && array_key_exists('year', $filters)) {
            $firstDay = new DateTime($filters['year'] . '-01-01');
            $lastDay = new DateTime($filters['year'] . '-12-31');

            $criterion1 = $criteria->getNewCriterion(OutgoingPaymentPeer::DATE, $firstDay, Criteria::GREATER_EQUAL);
            $criterion2 = $criteria->getNewCriterion(OutgoingPaymentPeer::DATE, $lastDay, Criteria::LESS_EQUAL);
            $criterion1->addAnd($criterion2);
            $criteria->addAnd($criterion1);
        }

        $this->outgoingPayments = OutgoingPaymentPeer::doSelect($criteria);
    }

}
