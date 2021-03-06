<?php

require_once 'lib/model/om/BaseSecurityPermPeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'security_perm' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class SecurityPermPeer extends BaseSecurityPermPeer {
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        if(count($criteria->getOrderByColumns())== 0)
        {
            $criteria->addAscendingOrderByColumn(SecurityPermPeer::ORDER_NO);
        }
        return parent::doSelect( $criteria,  $con);
    }
} // SecurityPermPeer
