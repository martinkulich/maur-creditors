<?php

require 'lib/model/om/BaseSecurityUserPeer.php';

/**
 * Skeleton subclass for performing query and update operations on the 'security_user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class SecurityUserPeer extends BaseSecurityUserPeer
{

    /**
     * @param string $email
     * @return SecurityUser
     */
    public static function retrieveByEmail($email)
    {
        $criteria = new Criteria();
        $criteria->add(self::EMAIL, $email);
        return self::doSelectOne($criteria);
    }

    public static function retrieveDetailsById($id)
    {
        $id = (integer) $id;
        $details = null;
        $user = self::retrieveByPK($id);
        if ($user) {
            $details = $user->getFullName();
        }
        return $details;
    }

    public static function retrieveByDetails($details, $limit = 10)
    {
        $criteria = new Criteria();

        $customCriteriaPattern = "lower(%s) like lower('%%%s%%')";
        $firstnameCustomCriteria = sprintf($customCriteriaPattern, self::FIRSTNAME, $details);
        $criterion1 = $criteria->getNewCriterion(self::FIRSTNAME, $firstnameCustomCriteria, Criteria::CUSTOM);

        $surnameCustomCriteria = sprintf($customCriteriaPattern, self::SURNAME, $details);
        $criterion2 = $criteria->getNewCriterion(self::SURNAME, $surnameCustomCriteria, Criteria::CUSTOM);

        $emailCustomCriteria = sprintf($customCriteriaPattern, self::EMAIL, $details);
        $criterion3 = $criteria->getNewCriterion(self::EMAIL, $emailCustomCriteria, Criteria::CUSTOM);

        $phoneCustomCriteria = sprintf($customCriteriaPattern, self::PHONE, $details);
        $criterion4 = $criteria->getNewCriterion(self::PHONE, $phoneCustomCriteria, Criteria::CUSTOM);


        $criterion1->addOr($criterion2);
        $criterion1->addOr($criterion3);
        $criterion1->addOr($criterion4);
        $criteria->addAnd($criterion1);

        $criteria->setLimit($limit);

        $return = array();
        foreach (self::doSelect($criteria) as $user) {
            $return[$user->getId()] = $user->getFullName();
        }

        return $return;
    }

    public static function retrieveByFullname($fullname)
    {
        $nameParts = explode(' ', $fullname);
        $criteria = new Criteria();
        $users = array();
        if (count($nameParts) == 2) {
            $criteria->add(self::FIRSTNAME, $nameParts[1]);
            $criteria->add(self::SURNAME, $nameParts[0]);

            foreach (self::doSelect($criteria) as $user) {
                $users[$user->getId()] = $user;
            }
        }

        return $users;
    }
}

// SecurityUserPeer
