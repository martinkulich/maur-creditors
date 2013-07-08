<?php

class CreditorsReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT
                '%currency_code%' as currency_code,
                sum(creditor_balance(cr.id, first_day(m.number, %year%), '%currency_code%')) as month_start_balance,
                sum(creditor_interest(cr.id, m.number, %year%, '%currency_code%')) as contractual_interest,
                sum(creditor_interest(cr.id,  %year%, '%currency_code%')/12) as month_interest,
                sum((creditor_interest(cr.id,  %year%, '%currency_code%')/12)*m.number) as month_interest_cumulative,
                sum(creditor_paid(cr.id, m.number, %year%, '%currency_code%')) as paid,
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

    public function getWhere()
    {
        $where = parent::getWhere();
        if($creditorId = $this->getFilter('creditor_id'))
        {
            $where .= $where === '' ? ' WHERE ' : ' AND ';
            $where .= 'cr.id='.$creditorId;
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
            'contractual_interest',
            'month_interest',
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
            'contractual_interest',
            'month_interest',
            'month_interest_cumulative',
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

}
