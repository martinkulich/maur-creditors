<?php

class BalanceReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                c.currency_code as currency_code,
                (cr.lastname::text || ' '::text || cr.firstname::text) as fullname, 
                sum(contract_balance(c.id, first_day(1, year('%date_to%'::date)), true))::integer as start_balance,
                sum(contract_balance(c.id, '%date_to%'::date, false))::integer as balance_no_capitalization_and_balance_reduction,
                sum(contract_balance(c.id, '%date_to%'::date, true))::integer as balance,
                sum(contract_balance(c.id, last_day(12, year('%date_to%'::date)), true))::integer as end_balance
            FROM creditor cr
            JOIN contract c ON cr.id = c.creditor_id
            WHERE (select count(cer.contract_id) from contract_excluded_report cer where cer.report_code = 'balance' AND cer.contract_id = c.id) = 0
            %where%
            GROUP BY c.currency_code, cr.id, cr.lastname, cr.firstname
            HAVING sum(contract_balance(c.id, '%date_to%'::date, true))::integer <> 0
            ORDER BY currency_code,  %order_by%
            ;
        ";
    }

    public function getColumns()
    {
        return array_merge(array(
            'fullname',
                ), $this->getCurrencyColumns()
        );
    }

    public function getTotalColumns()
    {
        return array(
            'start_balance',
            'balance',
            'balance_no_capitalization_and_balance_reduction',
            'end_balance',
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

    public function hasTotalColumn($column)
    {
        $totalColumns = $this->getTotalColumns();
        return in_array($column, $totalColumns);
    }

    public function getWhere()
    {
        $where = '';
        if ($creditorId = $this->getFilter('creditor_id')) {
            $where = ' AND cr.id = ' . $creditorId;
        }

        return $where;
    }

    public function getRequiredFilters()
    {
        return array('date_to');
    }

}
