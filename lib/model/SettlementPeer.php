<?php

require_once 'lib/model/om/BaseSettlementPeer.php';

/**
 * Skeleton subclass for performing query and update operations on the 'settlement' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class SettlementPeer extends BaseSettlementPeer
{
    const IN_PERIOD = 'in_period';
    const MANUAL = 'manual';
    const CLOSING = 'closing';
    const CLOSING_BY_REACTIVATION = 'closing_by_reactivation';
    const END_OF_FIRST_YEAR = 'end_of_first_year';
    const END_OF_YEAR = 'end_of_year';

    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        $criteria->addAscendingOrderByColumn(self::ID);
        return parent::doSelect($criteria, $con);
    }

    public static function settlementTypeExists($settlementType)
    {
        $translateService = ServiceContainer::getTranslateService();
        $settlementTypes = self::settlementTypes();
        return in_array($settlementType, $settlementTypes);
    }

    public static function settlementTypes()
    {
        return array(
            self::IN_PERIOD,
            self::MANUAL,
            self::CLOSING,
            self::CLOSING_BY_REACTIVATION,
            self::END_OF_FIRST_YEAR,
            self::END_OF_YEAR,
        );
    }
}

// SettlementPeer
