<?php

require_once 'lib/model/om/BaseAllocation.php';


/**
 * Skeleton subclass for representing a row from the 'allocation' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class Allocation extends BaseAllocation {

	/**
	 * Initializes internal state of Allocation object.
	 * @see        parent::__construct()
	 */
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}

    /**
     * @return Contract
     */
    public function getContract()
    {
        return $this->getSettlement()->getContract();
    }

    /**
     * @return Creditor
     */
    public function getCreditor()
    {
        return $this->getContract()->getCreditor();
    }

    public function getAllocated()
    {
        return $this->getPaid() + $this->getBalanceReduction();
    }


} // Allocation
