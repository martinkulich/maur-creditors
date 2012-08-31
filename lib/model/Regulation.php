<?php

require_once 'lib/model/om/BaseRegulation.php';

/**
 * Skeleton subclass for representing a row from the 'regulation' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class Regulation extends BaseRegulation
{

    public function hasManualInterest()
    {
        return $this->hasManual(SettlementPeer::MANUAL_INTEREST);
    }

    public function hasManualBalance()
    {
        return $this->hasManual(SettlementPeer::MANUAL_BALANCE);
    }

    public function hasManual($field)
    {
        $customCriteria = sprintf("date_part('year'::text, %s) = %s", SettlementPeer::DATE, $this->getRegulationYear());
        $criteria = new Criteria();
        $criteria
            ->add($field, true)
            ->add(SettlementPeer::CONTRACT_ID, $this->getContractId())
            ->add(SettlementPeer::DATE, $customCriteria, Criteria::CUSTOM);

        return SettlementPeer::doCount($criteria) > 0;
    }
}

// Regulation
