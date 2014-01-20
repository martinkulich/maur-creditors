<?php

require_once 'lib/model/om/BaseOutgoingPayment.php';

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

    public function getLongToString()
    {
        return sprintf("%s - %s/%s", format_date($this->getDate(), 'dd.MM.yyyy'), my_format_currency($this->getUnallocatedAmount(), $this->getCurrencyCode()), my_format_currency($this->getAmount(), $this->getCurrencyCode()));
    }

    public function getCreditor()
    {
        return $this->getSubject();
    }

    public function setCreditor($creditor)
    {
        $this->setSubject($creditor);
    }

    public function getAllocatedAmount()
    {
        return $this->getPaid() + $this->getBalanceReduction();
    }

    public function getUnallocatedAmount()
    {
        return $this->getAmount() - $this->getRefundation() - $this->getAllocatedAmount();
    }

    public function getPaid(Contract $contract = null)
    {
        $amount = 0;
        foreach ($this->getAllocations() as $allocation) {
            if ((!is_null($contract) && $contract->getId() === $allocation->getContractId()) || is_null($contract)) {
                $amount += $allocation->getPaid();
            }
        }
        return $amount;
    }

    public function getBalanceReduction(Contract $contract = null)
    {
        $amount = 0;
        foreach ($this->getAllocations() as $allocation) {
            if ((!is_null($contract) && $contract->getId() === $allocation->getContractId()) || is_null($contract)) {
                $amount += $allocation->getBalanceReduction();
            }
        }
        return $amount;
    }

}

// OutgoingPayment
