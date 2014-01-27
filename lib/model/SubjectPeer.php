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
} // SubjectPeer
