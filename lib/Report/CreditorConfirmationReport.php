<?php

class CreditorConfirmationReport extends Report
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                (cr.lastname::text || ' '::text || cr.firstname::text) as fullname, 
                cr.street || ', ' || cr.city || ', ' || cr.zip as address,
                c.currency_code as currency_code,
                sum(contract_balance(c.id, first_day(1, %year%), false)) as start_balance,
                sum(contract_balance(c.id, last_day(12, %year%), true)) as end_balance,
                sum(unpaid(c.id, %year%)) as unpaid,
                sum(paid(c.id, %year%)) as paid
            FROM creditor cr
            JOIN contract c ON c.creditor_id = cr.id
            %where%
            GROUP BY cr.lastname, cr.street, cr.city, cr.zip,  cr.firstname, currency_code
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
            'start_balance',
            'end_balance',
            'unpaid',
            'paid',
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

    protected function getRequiredFilters()
    {
        return array('year');
    }

}
