<?php

class ToPayReport extends Report
{

    public function getSqlPatter()
    {
        $endOfYearSettlementType = SettlementPeer::END_OF_YEAR;
        return "
            SELECT 
                (cr.lastname::text || ' '::text || cr.firstname::text) as fullname,
                co.name as contract_name,
                co.currency_code as currency_code,
                cr.bank_account as bank_account,
                (SELECT COALESCE(date, null) FROM previous_regular_settlement(co.id, '%date_to%'::date)) AS settlement_date,
                (SELECT COALESCE(id, null) FROM previous_regular_settlement(co.id, '%date_to%'::date)) AS settlement_id,
                contract_unpaid_regular(co.id,  '%date_to%'::date)::integer as to_pay
            FROM contract co
            JOIN creditor cr ON cr.id = co.creditor_id
            WHERE contract_unpaid_regular(co.id,  '%date_to%'::date)::integer <> 0 
            %where%
            GROUP BY
                cr.lastname,
                cr.firstname,
                co.name,
                cr.bank_account,
                co.id, 
                co.currency_code
                
            ORDER BY %order_by%, settlement_date, fullname
            ;
        ";
    }

    public function getColumns()
    {
        return array(
            'fullname',
            'contract_name',
            'bank_account',
            'settlement_date',
            'to_pay'
        );
    }

    public function getDateColumns()
    {
        return array('settlement_date');
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
        return 'settlement_date';
    }
    
    public function getTotalRow()
    {
        return 'currency_code';
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
    
    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);
        
        if($column == 'to_pay')
        {
            $formatedValue = link_to($formatedValue, '@settlement_pay?id='.$row['settlement_id'], array('class'=>'modal_link'));
        }
        
        return $formatedValue;
    }
    
}
