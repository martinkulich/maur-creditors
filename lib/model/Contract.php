<?php

require_once 'lib/model/om/BaseContract.php';

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
    protected $lastPayment = null;

    public function __toString()
    {
        return $this->getName();
    }

    public function getPaymentsAmount()
    {
        $paymentsAmount = 0;
        foreach ($this->getPayments() as $payment) {
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
        return sprintf('%s %%', parent::getInterestRate());
    }

    public function getPeriodInMonths()
    {
        return (12 / $this->getPeriod());
    }

    public function getSettlementsInYear($year)
    {
        $firstDate = new DateTime($year . '-01-01');
        $lastDate = new DateTime($year . '-12-30');
        $criteria = new Criteria;
        $criteria->add(SettlementPeer::DATE, $firstDate, Criteria::GREATER_EQUAL);
        $criteria->add(SettlementPeer::DATE, $lastDate, Criteria::LESS_EQUAL);

        return $this->getSettlements($criteria);
    }

    /**
     * @return Settlement
     */
    public function getLastSettlement($settlementType = SettlementPeer::IN_PERIOD)
    {
        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(SettlementPeer::DATE);
        $criteria->add(SettlementPeer::SETTLEMENT_TYPE, $settlementType);
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

    public function getSettlements($criteria = null, PropelPDO $con = null)
    {
        if (is_null($criteria)) {
            $criteria = new Criteria();
        }

        $criteria->addAscendingOrderByColumn(SettlementPeer::DATE);
        $criteria->addAscendingOrderByColumn(SettlementPeer::ID);

        return parent::getSettlements($criteria, $con);
    }

    public function getUnsettled(DateTime $dateTo = null)
    {
        $unsettled = 0;
        $criteria = new Criteria();

        if ($dateTo) {
            $criteria->add(SettlementPeer::DATE, $dateTo, Criteria::LESS_EQUAL);
        }
        foreach ($this->getSettlements($criteria) as $settlement) {
            $unsettled += $settlement->getUnsettled(false);
        }

        return $unsettled;
    }

    public function getLastSettlementDate($ormat = 'Y-m-d')
    {
        $lastSettlementDate = $this->getActivatedAt($ormat);
        if ($lastSettlement = $this->getLastSettlement()) {
            $lastSettlementDate = $lastSettlement->getDate($ormat);
        }

        return $lastSettlementDate;
    }

    /**
     * 
     * @param DateTime $date
     * @param Criteria $criteria
     * @return Settlement $settlement
     */
    public function getSettlementForDate(DateTime $date, Criteria $criteria = null)
    {
        if (is_null($criteria)) {
            $criteria = new Criteria();
        }
        $criteria->add(SettlementPeer::DATE, $date);
        $settlementsForDate = $this->getSettlements($criteria);

        $settlementForDate = null;
        if (count($settlementsForDate) > 0) {
            $settlementForDate = reset($settlementsForDate);
        }

        return $settlementForDate;
    }
}

// Contract
