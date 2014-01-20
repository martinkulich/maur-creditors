<?php

class ToPayReport extends ParentReport
{

    public function getSqlPatter()
    {
        $endOfYearSettlementType = SettlementPeer::END_OF_YEAR;
        return "
            SELECT 
                (cr.lastname::text || ' '::text || cr.firstname::text) as fullname,
                co.name as contract_name,
                ct.name as contract_type_name,
                co.id as contract_id,
                co.currency_code as currency_code,
                co.closed_at as closed_at,
                cr.bank_account as bank_account,
                (SELECT COALESCE(date, null) FROM previous_regular_settlement(co.id, '%date_to%'::date)) AS settlement_date,
                (SELECT COALESCE(id, null) FROM previous_regular_settlement(co.id, '%date_to%'::date)) AS settlement_id,
                contract_unpaid_regular(co.id, '%date_to%'::date, true)::integer as to_pay
            FROM contract co
            JOIN subject cr ON cr.id = co.creditor_id
            JOIN subject de ON de.id = co.debtor_id
            JOIN contract_type ct ON ct.id = co.contract_type_id
            WHERE (select count(cer.contract_id) from contract_excluded_report cer where cer.report_code = 'to_pay' AND cer.contract_id = co.id) = 0
            AND contract_unpaid_regular(co.id,  '%date_to%'::date, true)::integer <> 0
            AND co.capitalize != true
            %where%
            GROUP BY
                cr.lastname,
                cr.firstname,
                cr.bank_account,
                co.name,
                co.id,
                co.currency_code,
                co.closed_at,
                ct.name
            ORDER BY %order_by%, settlement_date
            ;
        ";
    }

    public function getColumns()
    {
        $columns = array(
            'fullname',
            'contract_type_name',
            'contract_name',
            'bank_account',
            'settlement_date',
            'to_pay',
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
        return array('to_pay');
    }
    
    public function getTotalColumns()
    {
        return $this->getCurrencyColumns();
    }
    
    public function getDefaultOrderBy()
    {
        return 'fullname';
    }
    
    public function getTotalRow()
    {
        return 'currency_code';
    }
    
    public function getWhere()
    {
        $where = 'AND '.$this->getDebtorCondition();
        if ($creditorId = $this->getFilter('creditor_id')) {
            $where .= ' AND cr.id = ' . $creditorId;
        }

        if ($contractTypeId = $this->getFilter('contract_type_id')) {
            $where .= ' AND co.contract_type_id = ' . $contractTypeId;
        }
        

        return $where;
    }
    
    public function getRequiredFilters()
    {
        return array('date_to');
    }
    
    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);
        
        if($column == 'to_pay')
        {
            $formatedValue = link_to($formatedValue, '@settlement_allocate?id='.$row['settlement_id'], array('class'=>'modal_link'));
        }
        elseif($column == 'contract_name')
        {
            $formatedValue = link_to($formatedValue, '@settlement_filter?settlement_filters[contract_id]='.$row['contract_id']);
        }
        elseif($column == 'exclude_from_report')
        {
            $formatedValue = link_to(ServiceContainer::getTranslateService()->__('exclude_from_report'), '@contract_excludeFromReport?report_type=to_pay&id='.$row['contract_id']);
        }
        
        return $formatedValue;
    }
    
}
