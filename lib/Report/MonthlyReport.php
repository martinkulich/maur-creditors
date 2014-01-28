<?php

class MonthlyReport extends ParentReport
{

    public function getSqlPatter()
    {
        return "
            SELECT
                %year% as year,
                %month% as month,
                c.currency_code as currency_code,
                sum(contract_paid(c.id, %month%, %year%)) as paid,
                sum(contract_balance(c.id, last_day(%month%, %year%), true)) as end_of_month_balance,
                sum(contract_capitalized(c.id, %month%, %year%))::integer AS capitalized,
                sum(contract_received_payments(c.id, %month%, %year%)) as received_payments,
                sum(contract_balance_increase(c.id, %month%, %year%)) as balance_increase

            FROM subject cr
            JOIN contract c ON c.creditor_id = cr.id
            JOIN subject de ON de.id = c.debtor_id
            %where%
            GROUP BY currency_code
            ORDER BY currency_code, %order_by%
                
            ;
        ";
    }

    public function getColumns()
    {
        return array(
            'end_of_month_balance',
            'paid',
            'capitalized',
            'received_payments',
            'balance_increase'
        );
    }

    public function getCurrencyColumns()
    {
        return $this->getColumns();
    }

    public function getRequiredFilters()
    {
        return array('month', 'year');
    }

    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);

        if ($column == 'paid') {
            $formatedValue = link_to($formatedValue, '@paid_detail?filter[year]='.$row['year'].'&filter[month]='.$row['month'].'&filter[currency_code]='.$row['currency_code'], array('class' => 'modal_link'));
        }
        return $formatedValue;
    }

    public function getWhere()
    {
        return ' where '.$this->getOwnerAsDebtorCondition();
    }



}
