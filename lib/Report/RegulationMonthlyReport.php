<?php

class RegulationMonthlyReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT
            %year% as year,
            %month% as month,
            (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname,
            cr.id AS creditor_id,
            '%currency_code%' AS currency_code,
            sum(creditor_balance(cr.id, first_day(%month%, %year%), '%currency_code%',  false)) AS month_start_balance,

            sum(creditor_interest(cr.id, %month%, %year%, '%currency_code%')) AS regulation,
            sum(creditor_interest(cr.id, %year%, '%currency_code%')/12) AS interest_in_the_recognition,
            sum(creditor_paid(cr.id,  %month%, %year%, '%currency_code%')) AS paid,
            sum(creditor_unpaid(cr.id, last_day(%month%, %year%), '%currency_code%')) AS unpaid,
            sum(creditor_capitalized(cr.id, %month%, %year%, '%currency_code%')) AS capitalized,
            sum(creditor_balance(cr.id, last_day(%month%, %year%), '%currency_code%', true)) AS month_end_balance
            FROM
            creditor cr
            WHERE true
            %where%
            group by cr.id
            HAVING sum(creditor_interest(cr.id, %year%, '%currency_code%'))::integer <> 0
            ORDER BY %order_by%
        ";
    }

    public function getWhere()
    {
        $conditions = array();
        if ($creditorId = $this->getFilter('creditor_id')) {
            $conditions[] = ' cr.id = ' . $creditorId;
        }



        $where = count($conditions) > 0 ? ' AND ' . implode(' AND ', $conditions) : '';
//        die(var_dump($where));
        return $where;
    }

    public function getColumns()
    {
        return array(
            'year',
            'month',
            'creditor_fullname',
            'month_start_balance',
            'regulation',
            'interest_in_the_recognition',
            'paid',
            'capitalized',
            'unpaid',
            'month_end_balance',
        );
    }

    public function getTotalColumns()
    {
        $totalColumns = $this->getCurrencyColumns();
        if (!$this->getFilter('year')) {

            $columnsToUnset = array(
                'unpaid',
                'month_start_balance',
                'month_end_balance',
            );
            $totalColumns = array_diff($totalColumns, $columnsToUnset);
        }
        return $totalColumns;
    }

    public function getTotalRow()
    {
        return 'currency_code';
    }

    public function getCurrencyColumns()
    {
        return array(
            'month_start_balance',
            'regulation',
            'interest_in_the_recognition',
            'paid',
            'capitalized',
            'unpaid',
            'month_end_balance'
        );
    }


    public function getDefaultOrderBy()
    {
        return 'year';
    }

    public function getColumnRowClass($column, array $row = array())
    {
        $class = parent::getColumnRowClass($column, $row);

        if ($column == 'regulation' && $row['manual_interest'] == true) {
            $class .= ' text-red';
        }
        return $class;
    }

    public function getRequiredFilters()
    {
        return array('month', 'year', 'currency_code');
    }

    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);

        if ($column == 'paid') {
            $formatedValue = link_to($formatedValue, '@creditor_paidDetail?filter[year]='.$row['year'].'&filter[month]='.$row['month'].'&id=' . $row['creditor_id'], array('class' => 'modal_link'));
        }
        return $formatedValue;
    }

}
