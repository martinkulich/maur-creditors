<?php

class UnpaidReport extends Report
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                cr.id as creditor_id,
                c.currency_code as currency_code,
                (cr.lastname::text || ' '::text || cr.firstname::text) AS creditor_fullname, 
                sum(contract_unpaid_regular(c.id, '%date_to%'))::integer AS creditor_unpaid_regular,
                sum(contract_unpaid(c.id, '%date_to%'))::integer AS creditor_unpaid
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
            'creditor_fullname',
            'creditor_unpaid',
            'creditor_unpaid_regular',
        );
    }

    public function getTotalColumns()
    {
        return array(
            'creditor_unpaid',
            'creditor_unpaid_regular',
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
