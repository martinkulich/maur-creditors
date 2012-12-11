<?php

class CreditorRevenueReport extends Report
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                cr.id as creditor_id,
                (cr.lastname::text || ' '::text || cr.firstname::text) AS creditor_fullname, 
                c.currency_code as currency_code,
                creditor_received_payments(cr.id, '%date_to%'::date) as creditor_received_payments,
                sum(contract_balance(c.id, '%date_to%'::date))::integer AS creditor_current_balance,
                sum(contract_balance(c.id, '%date_to%'::date)) - creditor_received_payments(cr.id, '%date_to%'::date) as creditor_balance_change,
                sum(contract_capitalized(c.id, '%date_to%'::date))::integer AS creditor_capitalized,
                sum(contract_balance_reduction(c.id, '%date_to%'::date))::integer AS creditor_balance_reduction,
                sum(contract_interest_regular(c.id, '%date_to%'::date))::integer AS creditor_interest_regular,
                sum(contract_paid(c.id, '%date_to%'::date))::integer AS creditor_paid,
                sum(contract_unpaid_regular(c.id, '%date_to%'::date))::integer AS creditor_unpaid_regular
            FROM creditor cr
            JOIN contract c ON c.creditor_id = cr.id
            %where%
            GROUP BY  currency_code, cr.id, cr.lastname, cr.firstname
            ORDER BY %order_by%
            ;
        ";
    }

    public function getColumns()
    {
        return array(
            'creditor_fullname',
            'creditor_received_payments',
            'creditor_current_balance',
            'creditor_balance_change',
            'creditor_capitalized',
            'creditor_balance_reduction',
            'creditor_interest_regular',
            'creditor_paid',
            'creditor_unpaid_regular',
        );
    }

    public function getTotalColumns()
    {
        return array(
            'creditor_received_payments',
            'creditor_current_balance',
            'creditor_balance_change',
            'creditor_capitalized',
            'creditor_balance_reduction',
            'creditor_interest_regular',
            'creditor_paid',
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
