<?php

abstract class CommitmentReport extends ParentReport
{

    public function getSqlPatter()
    {
        $endOfYearSettlementType = SettlementPeer::END_OF_YEAR;
        return "
            SELECT 
                (cr.lastname::text || ' '::text || cr.firstname::text) as creditor_fullname,
                (de.lastname::text || ' '::text || de.firstname::text) as debtor_fullname,
                c.name as contract_name,
                ct.name as contract_type_name,
                c.id as contract_id,
                c.currency_code as currency_code,
                c.closed_at as closed_at,
                cr.bank_account as bank_account,
                (SELECT COALESCE(date, null) FROM previous_regular_settlement(c.id, '%date_to%'::date)) AS settlement_date,
                (SELECT COALESCE(id, null) FROM previous_regular_settlement(c.id, '%date_to%'::date)) AS settlement_id,
                contract_unpaid_regular(c.id, '%date_to%'::date, true)::integer as amount
            FROM contract c
            JOIN subject cr ON cr.id = c.creditor_id
            JOIN subject de ON de.id = c.debtor_id
            JOIN contract_type ct ON ct.id = c.contract_type_id
            WHERE (select count(cer.contract_id) from contract_excluded_report cer where cer.report_code = '%report_code%' AND cer.contract_id = c.id) = 0
            AND contract_unpaid_regular(c.id,  '%date_to%'::date, true)::integer <> 0
            AND c.capitalize != true
            %where%
            GROUP BY
                cr.lastname,
                cr.firstname,
                de.lastname,
                de.firstname,
                cr.bank_account,
                c.name,
                c.id,
                c.currency_code,
                c.closed_at,
                ct.name
            ORDER BY %order_by%, settlement_date
            ;
        ";
    }

    public function getColumns()
    {
        $columns = array(
            'creditor_fullname',
            'debtor_fullname',
            'contract_type_name',
            'contract_name',
            'bank_account',
            'settlement_date',
            'amount',
            'closed_at',
        );

        if(sfContext::getInstance()->getUser()->hasCredential('contract.admin'))
        {
            $columns[] = 'exclude_from_report';

        }

        return $columns;
    }

    public function getDateColumns()
    {
        return array(
            'settlement_date',
            'closed_at',
        );
    }
    
    public function getCurrencyColumns()
    {
        return array('amount');
    }
    
    public function getTotalColumns()
    {
        return $this->getCurrencyColumns();
    }

    
    public function getTotalRow()
    {
        return 'currency_code';
    }

    
    public function getRequiredFilters()
    {
        return array('date_to');
    }
    
    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);
        
        if($column == 'amount')
        {
            $formatedValue = link_to($formatedValue, '@settlement_allocate?id='.$row['settlement_id'], array('class'=>'modal_link'));
        }
        elseif($column == 'contract_name')
        {
            $formatedValue = link_to($formatedValue, '@settlement_filter?settlement_filters[contract_id]='.$row['contract_id']);
        }

        
        return $formatedValue;
    }
    
}
