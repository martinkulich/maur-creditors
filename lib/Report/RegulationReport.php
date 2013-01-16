<?php

class RegulationReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT 
            creditor_fullname,
            creditor_id,
            contract_name,
            r.contract_id,
            regulation_year,
            start_balance,
            contract_activated_at,
            contract_balance,
            unpaid_in_past,
            regulation,
            r.paid as paid,
            paid_for_current_year,
            r.capitalized as capitalized,
            unpaid,
            end_balance,
            contract_currency_code as currency_code,
            count(s1.id)>0 as manual_balance,
            count(s2.id)>0 as manual_interest
            FROM regulation r
            LEFT JOIN settlement s1 on s1.contract_id = r.contract_id and s1.manual_balance = true
            LEFT JOIN settlement s2 on s2.contract_id = r.contract_id and s2.manual_interest = true
            WHERE (select count(cer.contract_id) from contract_excluded_report cer where cer.report_code = 'regulation' AND cer.contract_id = r.contract_id) = 0
            %where%
            GROUP BY 
            creditor_id,
            creditor_fullname,
            contract_name,
            r.contract_id,
            regulation_year,
            start_balance,
            contract_activated_at,
            contract_balance,
            unpaid_in_past,
            regulation,
            r.paid,
            paid_for_current_year,
            r.capitalized,
            unpaid,
            end_balance,
            contract_currency_code
            ORDER BY %order_by%, contract_name
            ;
        ";
    }

    public function getWhere()
    {
        $conditions = array();
        if ($creditorId = $this->getFilter('creditor_id')) {
            $conditions[] = ' creditor_id = ' . $creditorId;
        }
        
        if ($contractId = $this->getFilter('contract_id')) {
            $conditions[] = ' r.contract_id = ' . $contractId;
        }
        
        if ($year = $this->getFilter('year')) {
            $conditions[] = ' regulation_year = ' . $year;
        }
        $where = count($conditions) >0 ? ' AND '.implode(' AND ', $conditions) : '';
//        die(var_dump($where));
        return $where;
    }
    
    public function getColumns()
    {
        return array(
            'creditor_fullname',
            'contract_name',
            'regulation_year',
            'start_balance',
            'contract_activated_at',
            'contract_balance',
            'unpaid_in_past',
            'regulation',
            'paid',
            'paid_for_current_year',
            'capitalized',
            'unpaid',
            'end_balance',
        );
    }

    public function getTotalColumns()
    {
        $totalColumns = $this->getCurrencyColumns();
        if (!$this->getFilter('year')) {

            $columnsToUnset = array(
                'unpaid',
                'unpaid_in_past',
                'start_balance',
                'end_balance',
                'contract_balance',
            );
            $totalColumns = array_diff($totalColumns, $columnsToUnset);
        }
        $totalColumns = array_diff($totalColumns, array('contract_balance'));
        return $totalColumns;
    }

    public function getTotalRow()
    {
        return 'currency_code';
    }

    public function getCurrencyColumns()
    {
        return array(
            'start_balance',
            'contract_balance',
            'unpaid_in_past',
            'regulation',
            'paid',
            'paid_for_current_year',
            'capitalized',
            'unpaid',
            'end_balance'
        );
    }

    public function getDateColumns()
    {
        return array(
            'contract_activated_at',
        );
    }

    public function getDefaultOrderBy()
    {
        return 'regulation_year';
    }
    public function getColumnRowClass($column, array $row = array())
    {
        $class = parent::getColumnRowClass($column, $row);
        if ($column == 'contract_balance' && $row['manual_balance'] == true) {
            $class .= ' text-red';
        }

        if ($column == 'regulation' && $row['manual_interest'] == true) {
            $class .= ' text-red';
        }
        return $class;
    }

}
