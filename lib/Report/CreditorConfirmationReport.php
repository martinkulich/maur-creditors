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
                cr.id as creditor_id,
                sum(contract_balance(c.id, last_day(12, %year%-1), true)) as end_balance_of_previous_year,
                sum(contract_balance(c.id, last_day(12, %year%), true)) as end_balance_of_current_year,
                sum(contract_unpaid(c.id, last_day(12, %year%))) as unpaid,
                sum(contract_paid(c.id, %year%)) as paid,
                sum(contract_balance_reduction(c.id, %year%)) as balance_reduction,
                sum(contract_received_payments(c.id, %year%)) + sum(contract_capitalized(c.id, %year%)) as balance_increase,
                sum(contract_paid(c.id, %year%)) + sum(contract_balance_reduction(c.id, %year%)) as outgoing_payments
            FROM creditor cr
            JOIN contract c ON c.creditor_id = cr.id
            %where%
            GROUP BY 
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
        $where = '';
        if ($creditorId = $this->getFilter('creditor_id')) {
            $where = ' WHERE cr.id = ' . $creditorId;
        }

        return $where;
    }

    public function getColumns()
    {
        return array_merge(
                array(
            'fullname',
            'address',
                ), $this->getCurrencyColumns()
        );
    }

    public function getCurrencyColumns()
    {
        return array(
            'end_balance_of_previous_year',
            'balance_increase',
            'balance_reduction',
            'end_balance_of_current_year',
            'unpaid',
            'paid',
            'outgoing_payments',

        );
    }

    public function getTotalColumns()
    {
        return $this->getCurrencyColumns();
    }

    public function getTotalRow()
    {
        return 'currency_code';
    }

    public function getRequiredFilters()
    {
        return array('year');
    }

    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);

        if ($column == 'outgoing_payments') {
            $formatedValue = link_to($formatedValue, '@creditor_paidDetail?filter[year]='.$row['year'].'&id=' . $row['creditor_id'], array('class' => 'modal_link'));
        }
        return $formatedValue;
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
