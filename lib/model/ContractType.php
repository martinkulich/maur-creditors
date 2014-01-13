<?php

require_once 'lib/model/om/BaseContractType.php';


/**
 * Skeleton subclass for representing a row from the 'contract_type' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class ContractType extends BaseContractType
{

    public function __toString()
    {
        return $this->name;
    }

} // ContractType
