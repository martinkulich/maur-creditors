<?php

class AllocationService
{
    public function getAllocableOutgoingPaymentsCriteria(Creditor $creditor = null, OutgoingPayment $outgoingPayment = null)
    {
        $outgoingPaymentCriteria = new Criteria();
        $outgoingPaymentCriteria->addAscendingOrderByColumn(OutgoingPaymentPeer::DATE, Criteria::DESC);

        if($creditor)
        {
            $outgoingPaymentCriteria->add(OutgoingPaymentPeer::CREDITOR_ID, $creditor->getId());
        }


        $customCriteria = sprintf(
            "((select coalesce(sum(%s + %s), 0) from %s where %s = %s) < (%s - coalesce(%s,0)))",
            AllocationPeer::PAID,
            AllocationPeer::BALANCE_REDUCTION,
            AllocationPeer::TABLE_NAME,
            AllocationPeer::OUTGOING_PAYMENT_ID,
            OutgoingPaymentPeer::ID,
            OutgoingPaymentPeer::AMOUNT,
            OutgoingPaymentPeer::REFUNDATION
        );

        $criterion1 = $outgoingPaymentCriteria->getNewCriterion(OutgoingPaymentPeer::ID, $customCriteria, Criteria::CUSTOM);
        if ($outgoingPayment) {
            $criterion2 = $outgoingPaymentCriteria->getNewCriterion(OutgoingPaymentPeer::ID, $outgoingPayment->getId());
            $criterion1->addOr($criterion2);
        }
        $outgoingPaymentCriteria->addAnd($criterion1);

        return $outgoingPaymentCriteria;

    }
}
