<?php

class UnpaidReport extends Report
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                cr.id as creditor_id,
                c.currency_code as currency_code,
                (cr.lastname::text || ' '::text || cr.firstname::text) as fullname, 
                sum(contract_unpaid_regular(c.id, '%date_to%', true))::integer as unpaid_cumulative_regular,
                sum(contract_unpaid(c.id, '%date_to%', true))::integer as unpaid_cumulative
            FROM creditor cr
            JOIN contract c ON cr.id = c.creditor_id
            %where%
            GROUP BY 
                c.currency_code, 
                cr.id, 
                cr.lastname, 
                cr.firstname
            HAVING sum(contract_unpaid_regular(c.id, '%date_to%'::date, true))::integer <> 0
            ORDER BY currency_code,  %order_by%
            ;
        ";
    }

    public function getColumns()
    {
        return array_merge(array(
            'fullname',),
            $this->getCurrencyColumns()
        );
    }

    public function getTotalColumns()
    {
        return array(
            'unpaid_cumulative',
            'unpaid_cumulative_regular',
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



    protected function getRequiredFilters()
    {
        return array('date_to');
    }
    
    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);
        
        if($column == 'fullname')
        {
            $formatedValue = link_to($formatedValue, '@report_add_filter?report_type=to_pay&filter[creditor_id]='.$row['creditor_id']);
        }
        
        return $formatedValue;
    }

}
