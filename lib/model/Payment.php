<?php

require_once 'lib/model/om/BasePayment.php';

/**
 * Skeleton subclass for representing a row from the 'payment' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class Payment extends BasePayment
{

    public function getCreditor()
    {
        return $this->getContract()->getCreditor();
    }

    /**
     * Initializes internal state of Payment object.
     * @see        parent::__construct()
     */
    public function __construct()
    {
        // Make sure that parent constructor is always invoked, since that
        // is where any default values for this object are set.
        parent::__construct();
    }
}

// Payment
