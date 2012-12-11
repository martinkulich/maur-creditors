<?php

class MonthlyReport extends Report
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                c.currency_code as currency_code,
                sum(contract_paid(c.id, %month%, %year%)) as paid,
                sum(contract_balance(c.id, last_day(%month%, %year%))) as end_of_month_balance,
                sum(contract_capitalized(c.id, %month%, %year%))::integer AS capitalized,
                sum(contract_received_payments(c.id, %month%, %year%)) as received_payments
            FROM creditor cr
            JOIN contract c ON c.creditor_id = cr.id
            %where%
            GROUP BY currency_code
            --HAVING sum(contract_paid(c.id, %month%, %year%)) <> 0 OR sum(contract_balance(c.id, last_day(%month%, %year%))) <> 0
            ORDER BY currency_code, %order_by%
                
            ;
        ";
    }

    public function getColumns()
    {
        return array(
            'end_of_month_balance',
            'paid',
            'capitalized',
            'received_payments',
        );
    }

    public function getCurrencyColumns()
    {
        return $this->getColumns();
    }

    protected function getRequiredFilters()
    {
        return array('month', 'year');
    }

}
