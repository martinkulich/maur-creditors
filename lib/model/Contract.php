<?php

require 'lib/model/om/BaseContract.php';

/**
 * Skeleton subclass for representing a row from the 'contract' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class Contract extends BaseContract
{
    protected $lastSettlement = null;
    protected $lastPayment = null;

    public function __toString()
    {
        return $this->getName();
    }

    public function getPaymentsAmount()
    {
        $paymentsAmount = 0;
        foreach($this->getPayments() as $payment)
        {
            $paymentsAmount +=$payment->getAmount();
        }
        return $paymentsAmount;
    }

    public static function getPeriods()
    {
        $translateService = ServiceContainer::getTranslateService();
        return array(
            1 => $translateService->__('Per annum'),
            2 => $translateService->__('Per semestre'),
            4 => $translateService->__('Per quartale'),
            12 => $translateService->__('Per mensem'),
        );
    }

    public function getPeriodAsString()
    {
        $period = $this->getPeriod();
        $periods = self::getPeriods();

        return array_key_exists($period, $periods) ? $periods[$period] : null;
    }

    public function getInterestRateAsString()
    {
        return sprintf('%s%%', parent::getInterestRate());
    }

    public function getPeriodInMonths()
    {
        return (12 / $this->getPeriod());
    }

    /**
     * @return Settlement
     */
    public function getLastSettlement()
    {
        if (is_null($this->lastSettlement)) {
            $criteria = new Criteria();
            $criteria->addDescendingOrderByColumn(SettlementPeer::DATE);
            $settlements = $this->getSettlements($criteria);

            $this->lastSettlement = reset($settlements);
        }

        return $this->lastSettlement;
    }

    public function addSettlement(Settlement $settlement)
    {
        parent::addSettlement($settlement);

        if ($settlement->getDate()) {
            $criteria = new Criteria();
            $criteria->add(SettlementPeer::DATE, $settlement->getDate(), Criteria::GREATER_THAN);

            if ($this->countSettlements($criteria) == 0) {
                $this->lastSettlement = $settlement;
            }
        }
    }


    /**
     * @return Settlement
     */

    public function getFirstSettlement()
    {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(SettlementPeer::DATE);
        $criteria->setLimit(1);

        $settlements = $this->getSettlements($criteria);

        return reset($settlements);
    }

     /**
     * @return Paymenty
     */
    public function getLastPayment()
    {
        if (is_null($this->lastPayment)) {
            $criteria = new Criteria();
            $criteria->addDescendingOrderByColumn(PaymentPeer::DATE);
            $payments = $this->getPayments($criteria);

            $this->lastPayment = reset($payments);
        }

        return $this->lastPayment;
    }
}

// Contract
