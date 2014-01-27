<?php

class PaymentReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                co.currency_code as currency_code,
                (cr.lastname::text || ' '::text || cr.firstname::text) as creditor_fullname,
                (de.lastname::text || ' '::text || de.firstname::text) as debtor_fullname,
                co.name as contract_name,
                p.payment_type as payment_type,
                p.date as date,
                p.amount as amount,
                ba.name as bank_account,
                p.sender_bank_account as sender_bank_account,
                p.note as note
            FROM payment p
            JOIN contract co ON co.id = p.contract_id
            JOIN subject cr ON cr.id = co.creditor_id
            JOIN subject de ON de.id = co.debtor_id
            JOIN bank_account ba ON ba.id = p.bank_account_id
            %where%
            ORDER BY   %order_by%
            ;
        ";
    }

    public function getColumns()
    {
        return array(
            'debtor_fullname',
            'creditor_fullname',
            'contract_name',
            'payment_type',
             'date',
            'amount',
            'bank_account',
            'sender_bank_account',
        );
    }

    public function getTotalColumns()
    {
        return array(
            'amount',
        );
    }

    public function getDateColumns()
    {
        return array(
            'date'
        );
    }

    public function getTotalRow()
    {

        return 'currency_code';
    }

    public function getCurrencyColumns()
    {
        return $this->getTotalColumns();
    }

    public function hasTotalColumn($column)
    {
        $totalColumns = $this->getTotalColumns();
        return in_array($column, $totalColumns);
    }

    public function getWhere()
    {
        $where = '';
        $conditions = array();
        if ($creditorId = $this->getFilter('creditor_id')) {
            $conditions[] = ' cr.id = ' . $creditorId;
        }

        if ($debtorId = $this->getFilter('debtor_id')) {
            $conditions[] = ' de.id = ' . $debtorId;
        }

        if ($contractId = $this->getFilter('contract_id')) {
            $conditions[] = ' co.id = ' . $contractId;
        }

        if ($paymentType = $this->getFilter('payment_type')) {
            $conditions[] = ' p.payment_type = ' . "'".$paymentType."'";
        }

        if ($bankAccount = $this->getFilter('bank_account_id')) {
            $conditions[] = ' p.bank_account_id = ' . $bankAccount;
        }

        if ($senderBankAccount = $this->getFilter('sender_bank_account')) {
            $conditions[] = ' p.sender_bank_account = ' . "'".$senderBankAccount."'";
        }
        if ($dateFrom = $this->getFilter('date_from')) {
            $conditions[] = ' p.date >= ' . "'".$dateFrom."'::date";
        }
        if ($dateTo = $this->getFilter('date_to')) {
            $conditions[] = ' p.date <= ' . "'".$dateTo."'::date";
        }

        if(count($conditions)>0)
        {
            $where .= ' WHERE '.implode(' AND ', $conditions);
        }
        return $where;
    }

    public function getRequiredFilters()
    {
        return array(
            'date_from',
            'date_to'
        );
    }

    public function getFormatedRowValue($row, $column)
    {
        $formatedrowValue = $this->getFormatedValue($row, $column);
        if($column == 'payment_type')
        {
            $formatedrowValue = ServiceContainer::getTranslateService()->__($formatedrowValue);
        }

        return $formatedrowValue;
    }

}
