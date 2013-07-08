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
            c.name AS contract_name,
            c.id AS contract_id,
            c.currency_code AS currency_code,
            contract_balance(c.id, first_day(%month%, %year%), false) AS month_start_balance,
            CASE year_month(c.activated_at)
                WHEN year_month(%year%, %month%) THEN c.activated_at
                ELSE NULL::date
            END AS contract_activated_at,
            CASE year_month(c.activated_at)
                WHEN year_month(%year%, %month%)THEN c.amount
                ELSE NULL::numeric
            END AS contract_balance,
            contract_interest(c.id, %month%, %year%) AS regulation,
            contract_interest(c.id, %year%)/12 AS teoretical_regulation,
            contract_paid(c.id,  %month%, %year%) AS paid,
            contract_unpaid(c.id, last_day(%month%, %year%)) AS unpaid,
            contract_capitalized(c.id, %month%, %year%) AS capitalized,
            contract_balance(c.id, last_day(%month%, %year%), true) AS month_end_balance
            FROM
            contract c,
            creditor cr
            WHERE  cr.id = c.creditor_id
            %where%
            AND
            (
              c.closed_at is NULL
              OR
              (
                c.closed_at is NOT NULL
                AND
                last_day(%month%, %year%) < c.closed_at
              )

            );
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
            'contract_name',
            'month_start_balance',
            'contract_activated_at',
            'contract_balance',
            'regulation',
            'teoretical_regulation',
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
            'month_start_balance',
            'contract_balance',
            'regulation',
            'teoretical_regulation',
            'paid',
            'capitalized',
            'unpaid',
            'month_end_balance'
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
        return 'year';
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

    public function getRequiredFilters()
    {
        return array('month', 'year');
    }

}
