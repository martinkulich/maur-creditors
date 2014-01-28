<?php

abstract class SubjectProfitReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                (cr.lastname::text || ' '::text || cr.firstname::text) as creditor_fullname,
                (de.lastname::text || ' '::text || de.firstname::text) as debtor_fullname,
                c.currency_code as currency_code,
                cr.id as creditor_id,
                de.id as debtor_id,
                '%date_to%' as date_to,
                sum(contract_received_payments(c.id, '%date_to%'::date)) as received_payments,
                sum(contract_balance(c.id, '%date_to%'::date, true))::integer AS current_balance,
                sum(contract_balance(c.id, '%date_to%'::date, true)) - creditor_received_payments(cr.id, '%date_to%'::date)  as balance_change,
                sum(contract_capitalized(c.id, '%date_to%'::date))::integer as capitalized,
                sum(contract_balance_reduction(c.id, '%date_to%'::date))::integer as balance_reduction,
                sum(contract_balance_increase(c.id, '%date_to%'::date))::integer as balance_increase,
                sum(contract_interest_regular(c.id, '%date_to%'::date))::integer as interest_regular,
                sum(contract_paid(c.id, '%date_to%'::date))::integer as paid,
                sum(contract_unpaid(c.id, '%date_to%'::date))::integer AS unpaid,
                sum(contract_unpaid_regular(c.id, '%date_to%'::date))::integer AS unpaid_regular
            FROM contract c
            JOIN subject cr ON c.creditor_id = cr.id
            JOIN subject de On de.id = c.debtor_id
            WHERE (select count(cer.contract_id) from contract_excluded_report cer where cer.report_code = 'creditor_revenue' AND cer.contract_id = c.id) = 0
            %where%
            GROUP BY
            currency_code,
            cr.id,
            cr.lastname,
            cr.firstname,
            de.id,
            de.lastname,
            de.firstname
            ORDER BY currency_code, %order_by%
            ;
        ";
    }

    public function getColumns()
    {
        return array_merge(
                array(
            'creditor_fullname',
            'debtor_fullname',
                ), $this->getTotalColumns()
        );
    }

    public function getTotalColumns()
    {
        return array(
            'received_payments',
            'current_balance',
            'balance_change',
            'capitalized',
            'balance_increase',
            'balance_reduction',
            'interest_regular',
            'paid',
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


    public function getRequiredFilters()
    {
        return array('date_to');
    }

    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);

        if ($column == 'paid') {
            $formatedValue = link_to($formatedValue, '@subject_paidDetail?filter[date_to]='.$row['date_to'].'&id=' . $row['creditor_id'], array('class' => 'modal_link'));
        }
        return $formatedValue;
    }

}
