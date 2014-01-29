<?php

class CashFlowReport extends ParentReport
{
    public function getReportCode()
    {
        return 'cash_flow';
    }

    public function getSqlPatter()
    {
        return "
            SELECT
                %year% as year,
                %month% as month,
                c.currency_code as currency_code,
                  sum(
                    CASE WHEN %owner_is_debtor%  THEN
                      -contract_paid(c.id, %month%, %year%)
                    ELSE
                      contract_paid(c.id, %month%, %year%)
                    END
                  ) as paid,

                 sum(
                    CASE WHEN %owner_is_debtor%  THEN
                      -contract_balance(c.id, last_day(%month%, %year%), true)
                    ELSE
                      contract_balance(c.id, last_day(%month%, %year%), true)
                    END
                  ) as end_of_month_balance,

                 sum(
                    CASE WHEN %owner_is_debtor%  THEN
                      -contract_capitalized(c.id, %month%, %year%)
                    ELSE
                      contract_capitalized(c.id, %month%, %year%)
                    END
                  )::integer AS capitalized,

                 sum(
                    CASE WHEN %owner_is_debtor%  THEN
                      -contract_received_payments(c.id, %month%, %year%)
                    ELSE
                      contract_received_payments(c.id, %month%, %year%)
                    END
                  ) as received_payments,

                  sum(
                    CASE WHEN %owner_is_debtor%  THEN
                      -contract_balance_increase(c.id, %month%, %year%)
                    ELSE
                      contract_balance_increase(c.id, %month%, %year%)
                    END
                  ) as balance_increase

            FROM subject cr
            JOIN contract c ON c.creditor_id = cr.id
            JOIN subject de ON de.id = c.debtor_id
            %where%
            GROUP BY
            c.currency_code

            ORDER BY c.currency_code, %order_by%
                
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

    public function getReplacements()
    {
        $replacements = parent::getReplacements();
        $replacements['%owner_is_debtor%'] = $this->getOwnerAsDebtorCondition();

        return $replacements;
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
}
