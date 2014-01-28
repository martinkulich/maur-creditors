<?php

abstract class SubjectsReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT
                '%currency_code%' as currency_code,
                '%year%' as year,
                sum(contract_balance(c.id, first_day(m.number, %year%))) as month_start_balance,
                sum(contract_interest(c.id, m.number, %year%)) as regulation,
                sum(contract_interest(c.id,  %year%)/12) as interest_in_the_recognition,
                sum((contract_interest(c.id,  %year%)/12)*m.number) as interest_in_the_recognition_cumulative,
                sum(contract_paid(c.id, m.number, %year%)) as paid,
                sum(contract_balance_increase(c.id, m.number, %year%)) as balance_increase,
                sum(contract_capitalized(c.id, m.number, %year%)) as capitalized,
                sum(contract_unpaid(c.id, last_day(m.number, %year%))) as unpaid,
                sum(contract_balance(c.id, last_day(m.number, %year%), false)) as month_end_balance,
                m.number as month
            FROM months m,  contract c
            JOIN subject cr ON cr.id = c.creditor_id
            JOIN subject de ON de.id = c.debtor_id
            WHERE c.currency_code = '%currency_code%'
            %where%
            GROUP BY
                month,
                c.currency_code
            order by
            month
            ;
        ";
    }


    public function getColumns()
    {
        return array_merge(
            array(
//                'fullname',
                'month',
            ), $this->getCurrencyColumns()
        );
    }

    public function getTotalColumns()
    {
        return array(
            'regulation',
            'interest_in_the_recognition',
            'balance_increase',
            'paid',
            'capitalized',

        );
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
            'interest_in_the_recognition_cumulative',
            'balance_increase',
            'paid',
            'capitalized',
            'unpaid',
            'month_end_balance',
        );
    }


    public function getRequiredFilters()
    {
        return array('year', 'currency_code');
    }

    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);

        if ($column == 'paid') {
            if (array_key_exists('creditor_id', $row)) {
                $link = '@creditor_paidDetail?filter[year]=' . $row['year'] .'&filter[month]=' . $row['month'] . '&filter[currency_code]=' . $row['currency_code'] . '&id=' . $row['creditor_id'];
            } else {
                $link = '@paid_detail?filter[year]=' . $row['year'] .'&filter[month]=' . $row['month'] . '&filter[currency_code]=' . $row['currency_code'];
            }
            $formatedValue = link_to($formatedValue, $link, array('class' => 'modal_link'));
        }
        return $formatedValue;
    }

}
