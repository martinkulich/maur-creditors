<?php

require_once 'lib/model/om/BaseSubject.php';


/**
 * Skeleton subclass for representing a row from the 'subject' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class Subject extends BaseSubject {
    const SUBJECT_TYPE_FO = 'individual';
    const SUBJECT_TYPE_PO = 'corporation';

    const SUBJECT_TYPE_CODE_FO = 'fo';
    const SUBJECT_TYPE_CODE_PO = 'po';

    public static function getSubjectTypes()
    {
        $translateService = ServiceContainer::getTranslateService();
        return array(
            self::SUBJECT_TYPE_CODE_FO => $translateService->__(sfInflector::humanize(self::SUBJECT_TYPE_FO)),
            self::SUBJECT_TYPE_CODE_PO => $translateService->__(sfInflector::humanize(self::SUBJECT_TYPE_PO)),
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

    public function getSubjectType()
    {
        $creditorTypeCode = $this->getSubjectTypeCode();
        $creditorType = null;
        $creditorTypes = $this->getSubjectTypes();

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
} // Subject
