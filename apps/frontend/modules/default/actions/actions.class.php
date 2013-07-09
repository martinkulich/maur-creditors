<?php

/**
 * default actions.
 *
 * @package    rezervuj
 * @subpackage default
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{

    public function executeIndex(sfWebRequest $request)
    {

    }

    public function executeError404(sfWebRequest $request)
    {

    }

    public function executeModalSuccess(sfWebRequest $request)
    {

    }

    public function executePaidDetail(sfWebRequest $request)
    {

        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(OutgoingPaymentPeer::DATE);

        $filters = $request->getParameter('filter');

        if (is_array($filters) && array_key_exists('year', $filters)) {
            $monthFrom = array_key_exists('month', $filters) ? $filters['month'] : '01';
            $monthTo = array_key_exists('month', $filters) ? $filters['month'] : '12';
            $firstDay = new DateTime(sprintf('%s-%s-01', $filters['year'], $monthFrom));
            $lastDay = new DateTime(sprintf('%s-%s-01', $filters['year'], $monthTo));
            $lastDay->modify('last day of this month');

            $criterion1 = $criteria->getNewCriterion(OutgoingPaymentPeer::DATE, $firstDay, Criteria::GREATER_EQUAL);
            $criterion2 = $criteria->getNewCriterion(OutgoingPaymentPeer::DATE, $lastDay, Criteria::LESS_EQUAL);
            $criterion1->addAnd($criterion2);
            $criteria->addAnd($criterion1);
        }

        if(is_array($filters) && array_key_exists('currency_code', $filters))
        {
            $criteria->add(OutgoingPaymentPeer::CURRENCY_CODE, $filters['currency_code']);
        }

        $this->outgoingPayments = OutgoingPaymentPeer::doSelect($criteria);
    }
}
