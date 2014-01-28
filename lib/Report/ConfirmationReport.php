<?php

abstract class ConfirmationReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                %year% as year,
                (cr.lastname::text || ' '::text || cr.firstname::text) as creditor_fullname,
                cr.street || ', ' || cr.city || ', ' || cr.zip as creditor_address,
                cr.id as creditor_id,
                (de.lastname::text || ' '::text || de.firstname::text) as debtor_fullname,
                de.street || ', ' || de.city || ', ' || de.zip as debtor_address,
                de.id as creditor_id,
                c.currency_code as currency_code,
                ct.name as contract_type_name,
                sum(contract_balance(c.id, first_day(1, %year%), true)) as start_balance,
                sum(contract_balance(c.id, last_day(12, %year%), true)) as end_balance,
                sum(contract_unpaid(c.id, last_day(12, %year%))) as unpaid,
                sum(contract_capitalized(c.id, %year%)) as capitalized,
                sum(contract_paid(c.id, %year%)) as paid,
                sum(contract_balance_reduction(c.id, %year%)) as balance_reduction,
                sum(contract_received_payments(c.id, %year%)) + sum(contract_capitalized(c.id, %year%)) + sum(contract_balance_increase(c.id, %year%)) as balance_increase,
                sum(contract_received_payments(c.id, %year%)) as received_payments,
                sum(contract_paid(c.id, %year%)) + sum(contract_balance_reduction(c.id, %year%)) as outgoing_payments
            FROM subject cr
            JOIN contract c ON c.creditor_id = cr.id
            JOIN subject de On de.id = c.debtor_id
            JOIN contract_type ct ON ct.id = c.contract_type_id
            WHERE (
              c.closed_at is null
              OR
              (
                  c.closed_at is not null
                  And
                  (
                    year(c.closed_at) >= %year%
                    or
                    contract_unpaid(c.id, last_day(12, %year%))::integer <> 0
                    OR
                    contract_paid(c.id, %year%) <> 0
                  )
              )
            )
            %where%
            GROUP BY
                ct.name,
                cr.id, 
                cr.lastname, 
                cr.street, 
                cr.city, 
                cr.zip,  
                cr.firstname,
                de.id,
                de.lastname,
                de.street,
                de.city,
                de.zip,
                de.firstname,
                currency_code
            ORDER BY currency_code, %order_by%
                
            ;
        ";
    }


    public function getColumns()
    {
        return array_merge(
                array(
                    'contract_type_name',
            'creditor_fullname',
            'debtor_fullname',
            'creditor_address',
            'debtor_address',
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
        return array('year');
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
