<?php

require_once 'lib/model/om/BaseCreditor.php';

/**
 * Skeleton subclass for representing a row from the 'creditor' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class Creditor extends BaseCreditor
{
    const CREDITOR_TYPE_FO = 'individual';
    const CREDITOR_TYPE_PO = 'corporation';

    const CREDITOR_TYPE_CODE_FO = 'fo';
    const CREDITOR_TYPE_CODE_PO = 'po';

    public static function getCreditorTypes()
    {
        $translateService = ServiceContainer::getTranslateService();
        return array(
            self::CREDITOR_TYPE_CODE_FO => $translateService->__(sfInflector::humanize(self::CREDITOR_TYPE_FO)),
            self::CREDITOR_TYPE_CODE_PO => $translateService->__(sfInflector::humanize(self::CREDITOR_TYPE_PO)),
        );
    }

    public function __toString()
    {
        return $this->getFullName();
    }

    public function getFullName()
    {
        return $this->getLastname() . ' ' . $this->getFirstname();
    }

    public function getCreditorType()
    {
        $creditorTypeCode = $this->getCreditorTypeCode();
        $creditorType = null;
        $creditorTypes = $this->getCreditorTypes();

        return array_key_exists($creditorTypeCode, $creditorTypes) ? $creditorTypes[$creditorTypeCode] : null;
    }

    public function getOrderedGifts(Criteria $criteria = null)
    {
        if (is_null($criteria)) {
            $criteria = new Criteria();
        }
        $criteria->addAscendingOrderByColumn(GiftPeer::DATE, Criteria::DESC);

        return $this->getGifts($criteria);
    }
}

// Creditor
