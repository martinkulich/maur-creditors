<?php

require_once 'lib/model/om/BaseSettlement.php';

/**
 * Skeleton subclass for representing a row from the 'settlement' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class Settlement extends BaseSettlement
{

    public function __toString()
    {
        return sprintf('%s (%s)', format_date($this->getDate(), 'D'), $this->getTranslatedSettlementType());
    }
    public function getCreditor()
    {
        return $this->getContract()->getCreditor();
    }

    /**
     * Initializes internal state of Settlement object.
     * @see        parent::__construct()
     */
    public function __construct()
    {
        // Make sure that parent constructor is always invoked, since that
        // is where any default values for this object are set.
        parent::__construct();
    }

    /**
     *
     * @return Settlement
     */
    public function getPreviousSettlementOfContract()
    {
        $previousSettlement = null;
        if ($contract = $this->getContract()) {
            $criteria = new Criteria();
            $criteria->add(SettlementPeer::DATE, $this->getDate(), Criteria::LESS_THAN);
            $criteria->addDescendingOrderByColumn(SettlementPeer::DATE);
            $criteria->setLimit(1);

            $settlements = $contract->getSettlements($criteria);
            $previousSettlement = reset($settlements);
        }

        return $previousSettlement;
    }

    public function getNextSettlementsOfContract()
    {
        $nextSettlements = array();
        if ($contract = $this->getContract()) {
            $criteria = new Criteria();
            $criteria->add(SettlementPeer::DATE, $this->getDate(), Criteria::GREATER_THAN);
            $criteria->addAscendingOrderByColumn(SettlementPeer::DATE);

            $nextSettlements = $contract->getSettlements($criteria);
        }

        return $nextSettlements;
    }

    public function getUnsettled($onlyPositive = true)
    {
        $unsettled = round($this->getInterest() - $this->getPaid() - $this->getCapitalized(), 2);
        return $unsettled > 0 ? $unsettled : ($onlyPositive ? 0 : $unsettled);
    }

    public function getTranslatedSettlementType()
    {
        return sfContext::getInstance()->getI18N()->__($this->getSettlementType());
    }

    public function getContractInterestRate()
    {
        return $this->getContract()->getInterestRateAsString();
    }

    public function getUnsettledCumulative()
    {
        return $this->getContract()->getUnsettled(new DateTime($this->getDate()));
    }

    public function isSettlementType($settlementType)
    {
        return $this->getSettlementType() == $settlementType;
    }

    public function isFirstOfContract()
    {
        $criteria = new Criteria();
        $criteria
            ->add(SettlementPeer::DATE, $this->getDate(), Criteria::LESS_THAN)
            ->add(SettlementPeer::CONTRACT_ID, $this->getContract()->getId());

        return SettlementPeer::doCount($criteria) === 0;
    }

    public function getPaid()
    {
        $paid = 0;
        foreach($this->getAllocations() as $allocation)
        {
            $paid += $allocation->getPaid();
        }

        return $paid;
    }

    public function getBalanceReduction()
    {
        $balanceReduction = 0;
        foreach($this->getAllocations() as $allocation)
        {
            $balanceReduction += $allocation->getBalanceReduction();
        }

        return $balanceReduction;
    }

    public function getBalanceIncrease()
    {
        return $this->getPayment() ? $this->getPayment()->getAmount() : 0;
    }

    public function getBalanceAfterSettlement()
    {
        return $this->getBalance()+$this->getCapitalized()-$this->getBalanceReduction();
    }




}

// Settlement
