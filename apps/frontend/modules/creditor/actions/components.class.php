<?php

class creditorComponents extends sfComponents
{

    public function executePaidDetail()
    {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(OutgoingPaymentPeer::DATE);
        $criteria->add(OutgoingPaymentPeer::CREDITOR_ID, $this->filters['creditor_id']);


        if (is_array($this->filters) && array_key_exists('year', $this->filters)) {
            $firstDay = new DateTime($this->filters['year'] . '-01-01');
            $lastDay = new DateTime($this->filters['year'] . '-12-31');

            $criterion1 = $criteria->getNewCriterion(OutgoingPaymentPeer::DATE, $firstDay, Criteria::GREATER_EQUAL);
            $criterion2 = $criteria->getNewCriterion(OutgoingPaymentPeer::DATE, $lastDay, Criteria::LESS_EQUAL);
            $criterion1->addAnd($criterion2);
            $criteria->addAnd($criterion1);
        }
        $this->outgoingPayments = OutgoingPaymentPeer::doSelect($criteria);
    }

}
