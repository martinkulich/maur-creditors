<?php

require 'lib/model/om/BaseSettlement.php';

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

    public function getUnsettled()
    {
        $unsettled = round($this->getInterest() - $this->getPaid() - $this->getCapitalized(), 2);
        return $unsettled > 0 ? $unsettled : 0;
    }
}

// Settlement
