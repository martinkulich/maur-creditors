<?php

class CreditorsReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT
                '%currency_code%' as currency_code,
                creditor_balance(%creditor_id%, first_day(m.number, %year%), '%currency_code%') as month_start_balance,
                creditor_interest(%creditor_id%, m.number, %year%, '%currency_code%') as contractual_interest,
                creditor_interest(%creditor_id%,  %year%, '%currency_code%')/12 as month_interest,
                (creditor_interest(%creditor_id%,  %year%, '%currency_code%')/12)*m.number as month_interest_cumulative,
                creditor_paid(%creditor_id%, m.number, %year%, '%currency_code%') as paid,
                creditor_capitalized(%creditor_id%, m.number, %year%, '%currency_code%') as capitalized,
                creditor_unpaid(%creditor_id%, last_day(m.number, %year%), '%currency_code%') as unpaid,
                creditor_balance(%creditor_id%, last_day(m.number, %year%), '%currency_code%', false) as month_end_balance,
                m.number as month
            FROM creditor cr, months m
            GROUP BY
                month
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
        return array('creditor_id', 'year', 'currency_code');
    }

}
