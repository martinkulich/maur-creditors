<?php

class BalanceReport extends Report
{
    public function getSqlPatter()
    {
        return "
            SELECT 
                c.currency_code as currency_code,
                (cr.lastname::text || ' '::text || cr.firstname::text) as fullname, 
                sum(contract_balance(c.id, '%date_to%'::date, true))::integer as balance
            FROM creditor cr
            JOIN contract c ON cr.id = c.creditor_id
            %where%
            GROUP BY c.currency_code, cr.id, cr.lastname, cr.firstname
            HAVING sum(contract_balance(c.id, '%date_to%'::date, true))::integer <> 0
            ORDER BY currency_code,  %order_by%
            ;
        ";
    }
    
    public function getColumns()
    {
        return array(
            'fullname',
            'balance',
        );
    }
    
    public function getTotalColumns()
    {
        return array('balance');
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
    
    
    public function getFormatedValue($row, $column)
    {
        switch ($column) {
            case 'balance':
                $formatedValue = my_format_currency($row[$column], $row['currency_code']);
                break;

            default:
                $formatedValue = $row[$column];
                break;
        }
        
        return $formatedValue;
    }
    

    
    public function getWhere()
    {
        $where = '';
        if($creditorId = $this->getFilter('creditor_id'))
        {
            $where = ' WHERE cr.id = '.$creditorId;
        }

        return $where;
    }
    
    protected function getRequiredFilters()
    {
        return array('date_to');
    }

}
