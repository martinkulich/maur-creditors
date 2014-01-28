<?php

require_once 'lib/model/om/BaseSubjectPeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'subject' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class SubjectPeer extends BaseSubjectPeer
{

    public static function getOwner()
    {
        $criteria = new Criteria();
        $criteria->Add(self::IDENTIFICATION_NUMBER, sfConfig::get('app_owner_identification_number'));
        return self::doSelectOne($criteria);
    }

    public static function getDebtorsCriteria(Criteria $criteria = null)
    {
        $criteria = null === $criteria ? new Criteria() : $criteria;
        $criteria->addAscendingOrderByColumn(SubjectPeer::LASTNAME);
        $criteria->addJoin(SubjectPeer::ID, ContractPeer::DEBTOR_ID);


        return $criteria;
    }

    public static function getCreditorsCriteria(Criteria $criteria = null)
    {
        $criteria = null === $criteria ? new Criteria() : $criteria;
        $criteria->addAscendingOrderByColumn(SubjectPeer::LASTNAME);
        $criteria->addJoin(SubjectPeer::ID, ContractPeer::CREDITOR_ID);

        return $criteria;
    }

    public static function getOwnerCriteria(Criteria $criteria = null)
    {
        $criteria = null === $criteria ? new Criteria() : $criteria;
        $criteria->add(SubjectPeer::IDENTIFICATION_NUMBER, sfConfig::get('app_owner_identification_number'));
        return $criteria;
    }

    public static function getExcludeOwnerCriteria(Criteria $criteria = null)
    {
        $criteria = null === $criteria ? new Criteria() : $criteria;
        $criteria->add(SubjectPeer::IDENTIFICATION_NUMBER, sfConfig::get('app_owner_identification_number'), Criteria::NOT_EQUAL);
        return $criteria;
    }

    public static function getOwnerAsDebtorCriteria(Criteria $criteria = null)
    {
        return static::getOwnerCriteria(static::getDebtorsCriteria($criteria));
    }


    public static function getOwnerAsCreditorCriteria(Criteria $criteria = null)
    {
        return static::getOwnerCriteria(static::getCreditorsCriteria($criteria));
    }

    public static function getDebtorsExcludeOwnerCriteria(Criteria $criteria = null)
    {
        $criteria = self::getDebtorsCriteria($criteria);

        return self::getExcludeOwnerCriteria($criteria);

    }

    public static function getCreditorsExcludeOwnerCriteria(Criteria $criteria = null)
    {
        $criteria = self::getCreditorsCriteria($criteria);

        return self::getExcludeOwnerCriteria($criteria);

    }

} // SubjectPeer
