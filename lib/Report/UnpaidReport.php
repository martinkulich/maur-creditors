<?php

class UnpaidReport extends Report
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                c.currency_code as currency_code,
                (cr.lastname::text || ' '::text || cr.firstname::text) as fullname, 
                sum(contract_unpaid_regular(c.id, '%date_to%'))::integer as unpaid_regular,
                sum(contract_unpaid(c.id, '%date_to%'))::integer as unpaid
            FROM creditor cr
            JOIN contract c ON cr.id = c.creditor_id
            %where%
            GROUP BY c.currency_code, cr.id, cr.lastname, cr.firstname
            HAVING sum(contract_unpaid(c.id, '%date_to%'::date))::integer <> 0
            ORDER BY currency_code,  %order_by%
            ;
        ";
    }

    public function getColumns()
    {
        return array(
            'fullname',
            'unpaid',
            'unpaid_regular',
        );
    }

    public function getTotalColumns()
    {
        return array(
            'unpaid',
            'unpaid_regular',
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
        return array('date_to');
    }

}
