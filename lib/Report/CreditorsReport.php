<?php

class CreditorsReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT
                '%currency_code%' as currency_code,
                '%year%' as year,
                %creditor_row%
                sum(creditor_balance(cr.id, first_day(m.number, %year%), '%currency_code%')) as month_start_balance,
                sum(creditor_interest(cr.id, m.number, %year%, '%currency_code%')) as regulation,
                sum(creditor_interest(cr.id,  %year%, '%currency_code%')/12) as interest_in_the_recognition,
                sum((creditor_interest(cr.id,  %year%, '%currency_code%')/12)*m.number) as interest_in_the_recognition_cumulative,
                sum(creditor_paid(cr.id, m.number, %year%, '%currency_code%')) as paid,
                sum(creditor_balance_increase(cr.id, m.number, %year%, '%currency_code%')) as balance_increase,
                sum(creditor_capitalized(cr.id, m.number, %year%, '%currency_code%')) as capitalized,
                sum(creditor_unpaid(cr.id, last_day(m.number, %year%), '%currency_code%')) as unpaid,
                sum(creditor_balance(cr.id, last_day(m.number, %year%), '%currency_code%', false)) as month_end_balance,
                m.number as month
            FROM creditor cr, months m
            %where%
            GROUP BY
                month
            order by
            month
            ;
        ";
    }

    public function getReplacements()
    {
        $replacements = parent::getReplacements();
        $creditorId = $this->getFilter('creditor_id');
        $replacements['%creditor_row%'] = $creditorId ? $creditorId . ' as creditor_id, ' : '';

        return $replacements;
    }

    public function getWhere()
    {
        $where = parent::getWhere();
        if ($creditorId = $this->getFilter('creditor_id')) {
            $where .= $where === '' ? ' WHERE ' : ' AND ';
            $where .= 'cr.id=' . $creditorId;
        }
        return $where;
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
