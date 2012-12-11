<?php

class MonthlyReport extends Report
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                (cr.lastname::text || ' '::text || cr.firstname::text) AS creditor_fullname,    
                c.currency_code as currency_code,
                sum(contract_paid(c.id, %month%, %year%)) as paid,
                sum(contract_balance(c.id, last_day(%month%, %year%))) as end_of_month_balance
            FROM creditor cr
            JOIN contract c ON c.creditor_id = cr.id
            %where%
            GROUP BY currency_code, cr.lastname, cr.firstname
            ORDER BY %order_by%
            ;
        ";
    }

    public function getColumns()
    {
        return array(
            'creditor_fullname',
            'end_of_month_balance',
            'paid',
        );
    }

    public function getTotalColumns()
    {
        return array(
            'end_of_month_balance',
            'paid',
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


    public function getWhere()
    {
        $where = '';
        if ($creditorId = $this->getFilter('creditor_id')) {
            $where = ' WHERE cr.id = ' . $creditorId;
        }

        return $where;
    }

    protected function getRequiredFilters()
    {
        return array('month', 'year');
    }

}
