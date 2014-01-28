<?php

abstract class RegulationMonthlyReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT
            %year% as year,
            %month% as month,
            (cr.lastname::text || ' '::text) || cr.firstname::text AS creditor_fullname,
            cr.id AS creditor_id,
            (de.lastname::text || ' '::text) || de.firstname::text AS debtor_fullname,
            de.id AS debtor_id,
            c.currency_code AS currency_code,
            sum(contract_balance(c.id, first_day(%month%, %year%),  false)) AS month_start_balance,

            sum(contract_interest(c.id, %month%, %year%)) AS regulation,
            sum(contract_interest(c.id, %year%)/12) AS interest_in_the_recognition,
            sum(contract_paid(c.id,  %month%, %year%)) AS paid,
            sum(contract_unpaid(c.id, last_day(%month%, %year%))) AS unpaid,
            sum(contract_capitalized(c.id, %month%, %year%)) AS capitalized,
            sum(contract_balance(c.id, last_day(%month%, %year%), true)) AS month_end_balance
            FROM
            contract c
            JOIN
            subject cr on c.creditor_id = cr.id
            JOIN
            subject de on c.debtor_id = de.id

            WHERE true
            %where%
            group by
            cr.id,
            cr.lastname,
            cr.firstname,
            de.id,
            de.lastname,
            de.firstname,
            c.currency_code
            HAVING sum(contract_interest(c.id, %year%))::integer <> 0
            ORDER BY %order_by%
        ";
    }


    public function getColumns()
    {
        return array(
            'year',
            'month',
            'debtor_fullname',
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

        if ($column == 'regulation' && isset($row['manual_interest']) && $row['manual_interest'] == true) {
            $class .= ' text-red';
        }
        return $class;
    }

    public function getRequiredFilters()
    {
        return array('month', 'year');
    }

    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);

        if ($column == 'paid') {
            $formatedValue = link_to($formatedValue, '@subject_paidDetail?filter[year]='.$row['year'].'&filter[month]='.$row['month'].'&id=' . $row['creditor_id'], array('class' => 'modal_link'));
        }
        return $formatedValue;
    }

}
