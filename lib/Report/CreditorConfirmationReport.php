<?php

class CreditorConfirmationReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                %year% as year,
                (cr.lastname::text || ' '::text || cr.firstname::text) as fullname, 
                cr.street || ', ' || cr.city || ', ' || cr.zip as address,
                c.currency_code as currency_code,
                ct.name as contract_type_name,
                cr.id as creditor_id,
                sum(contract_balance(c.id, first_day(1, %year%), true)) as start_balance,
                sum(contract_balance(c.id, last_day(12, %year%), true)) as end_balance,
                sum(contract_unpaid(c.id, last_day(12, %year%))) as unpaid,
                sum(contract_capitalized(c.id, %year%)) as capitalized,
                sum(contract_paid(c.id, %year%)) as paid,
                sum(contract_balance_reduction(c.id, %year%)) as balance_reduction,
                sum(contract_received_payments(c.id, %year%)) + sum(contract_capitalized(c.id, %year%)) + sum(contract_balance_increase(c.id, %year%)) as balance_increase,
                sum(contract_received_payments(c.id, %year%)) as received_payments,
                sum(contract_paid(c.id, %year%)) + sum(contract_balance_reduction(c.id, %year%)) as outgoing_payments
            FROM creditor cr
            JOIN contract c ON c.creditor_id = cr.id
            JOIN contract_type ct ON ct.id = c.contract_type_id
            %where%
            GROUP BY
                ct.name,
                cr.id, 
                cr.lastname, 
                cr.street, 
                cr.city, 
                cr.zip,  
                cr.firstname, 
                currency_code
            ORDER BY currency_code, %order_by%
                
            ;
        ";
    }

    public function getWhere()
    {
        $where = 'WHERE true ';
        if ($creditorId = $this->getFilter('creditor_id')) {
            $where .= ' AND cr.id = ' . $creditorId;
        }

        if ($contractTypeId = $this->getFilter('contract_type_id')) {
            $where .= ' AND c.contract_type_id = ' . $contractTypeId;
        }

        return $where;
    }

    public function getColumns()
    {
        return array_merge(
                array(
                    'contract_type_name',
            'fullname',
            'address',
                ), $this->getCurrencyColumns()
        );
    }

    public function getCurrencyColumns()
    {
        return array(
            'start_balance',
            'received_payments',
            'capitalized',
            'balance_increase',
            'balance_reduction',
            'unpaid',
            'paid',
            'outgoing_payments',
            'end_balance',

        );
    }

    public function getTotalColumns()
    {
        return array();
    }

    public function getTotalRow()
    {
        return 'currency_code';
    }

    public function getRequiredFilters()
    {
        return array('year', 'creditor_id');
    }

    public function getColumnHeader($column)
    {
        if($column == 'paid')
        {
            $column = 'paid interests';
        }
        elseif($column == 'unpaid')
        {
            $column = 'unpaid interests';
        }
        elseif($column == 'balance_reduction')
        {
            $column = 'Balance reduction during year';
        }
        elseif($column == 'balance_increase')
        {
            $column = 'Balance increase during year';
        }

        return $column;
    }

}
