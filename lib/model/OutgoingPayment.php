<?php

require 'lib/model/om/BaseOutgoingPayment.php';

/**
 * Skeleton subclass for representing a row from the 'outgoing_payment' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class OutgoingPayment extends BaseOutgoingPayment
{

    public function __toString()
    {
        return sprintf("%s - %s", format_date($this->getDate(), 'dd.MM.yyyy'), my_format_currency($this->getAmount(), $this->getCurrencyCode()));
    }
    
    
    public function getUsedAmount()
    {
        $amount = 0;
        foreach($this->getSettlements() as $settlement)
        {
            $amount += $settlement->getPaid();
        }
        return $amount;
    }
    
    public function getUnusedAmount()
    {
        return $this->getAmount() - $this->getUsedAmount();
    }

}

// OutgoingPayment
