<?php

abstract class UnpaidReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                cr.id as creditor_id,
                c.currency_code as currency_code,
                (cr.lastname::text || ' '::text || cr.firstname::text) as creditor_fullname,
                (de.lastname::text || ' '::text || de.firstname::text) as debtor_fullname,
                sum(contract_unpaid_regular(c.id, '%date_to%', true))::integer as unpaid_cumulative_regular,
                sum(contract_unpaid(c.id, '%date_to%', true))::integer as unpaid_cumulative
            FROM subject cr
            JOIN contract c ON cr.id = c.creditor_id
            JOIN subject de ON c.debtor_id = de.id
            WHERE (select count(cer.contract_id) from contract_excluded_report cer where cer.report_code = '%report_code%' AND cer.contract_id = c.id) = 0
            %where%
            GROUP BY 
                c.currency_code, 
                cr.id, 
                cr.lastname, 
                cr.firstname,
                de.id,
                de.lastname,
                de.firstname
            HAVING sum(contract_unpaid_regular(c.id, '%date_to%'::date, true))::integer <> 0
            ORDER BY currency_code,  %order_by%
            ;
        ";
    }

    public function getColumns()
    {
        return array_merge(array(
            'debtor_fullname',
            'creditor_fullname',
            ),
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



    public function getRequiredFilters()
    {
        return array('date_to');
    }
}
