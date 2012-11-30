<?php

class ReportService
{

    public function getData($reportType, array $filters = array())
    {
        $dataFunction = 'getData' . sfInflector::camelize($reportType);

        return $this->$dataFunction($filters);
    }

    public function getDataUnpaid(array $filters = array(), $excludeEndOfYearSettlement = false)
    {
        $main = "
            SELECT 
                cr.id as creditor_id,
                c.currency_code as currency_code,
                (cr.lastname::text || ' '::text || cr.firstname::text) AS creditor_fullname, 
                sum(contract_unpaid_regular(c.id, '%date_to%'))::integer AS creditor_unpaid_regular,
                sum(contract_unpaid(c.id, '%date_to%'))::integer AS creditor_unpaid
            FROM creditor cr
            JOIN contract c ON cr.id = c.creditor_id
            GROUP BY c.currency_code, cr.id, cr.lastname, cr.firstname
            HAVING sum(contract_unpaid(c.id, '%date_to%'::date))::integer <> 0
            ORDER BY %order_by%
            ;";
        $replacements = array(
            '%order_by%' => 'creditor_fullname',
            '%date_to%' => $filters['date_to'],
        );
        $columns = array(
            'creditor_unpaid' => 1,
            'creditor_unpaid_regular' => 1,
        );

        $orderBy = $this->getOrderBy($filters, array_merge(array('creditor_fullname'), array_keys($columns)));
        if ($orderBy) {
            $replacements['%order_by%'] = $orderBy;
        }
        $replacements = $this->getReplacements($replacements, $filters);
        $rows = $this->procesQueryFetchAll($main, $replacements);
        $data = array(
            'total' => array(),
            'rows' => $rows,
            'currency_codes' => array(),
            'columns' => $columns,
        );

        foreach ($columns as $column => $isCurrency) {
            foreach ($rows as $row) {
                $data['currency_codes'][$row['currency_code']] = $row['currency_code'];
                if (!isset($data['total'][$column][$row['currency_code']])) {
                    $data['total'][$column][$row['currency_code']] = 0;
                }
                $data['total'][$column][$row['currency_code']] += $row[$column];
            }
        }
        return $data;
    }

    public function getDataInterestsToPay(array $filters = array())
    {
        return $this->getDataUnpaid($filters, true);
    }

    public function getDataBalance(array $filters = array())
    {
        $main = "
            SELECT 
                cr.id as creditor_id,
                c.currency_code as currency_code,
                (cr.lastname::text || ' '::text || cr.firstname::text) AS creditor_fullname, 
                sum(contract_balance(c.id, '%date_to%'::date))::integer AS creditor_balance
            FROM creditor cr
            JOIN contract c ON cr.id = c.creditor_id
            GROUP BY c.currency_code, cr.id, cr.lastname, cr.firstname
            HAVING sum(contract_balance(c.id, '%date_to%'::date))::integer <> 0
            ORDER BY %order_by%
            ;";
        $replacements = array(
            '%order_by%' => 'creditor_fullname',
            '%date_to%' => $filters['date_to'],
        );

        $columns = array(
            'creditor_balance' => 1,
        );

        $orderBy = $this->getOrderBy($filters, array_merge(array('creditor_fullname'), array_keys($columns)));
        if ($orderBy) {
            $replacements['%order_by%'] = $orderBy;
        }

        $replacements = $this->getReplacements($replacements, $filters);
        $rows = $this->procesQueryFetchAll($main, $replacements);
        $data = array(
            'total' => array(),
            'rows' => $rows,
            'currency_codes' => array(),
            'columns' => $columns,
        );

        foreach ($rows as $row) {

            $data['currency_codes'][$row['currency_code']] = $row['currency_code'];
            if ($row['creditor_balance']) {
                if (!isset($data['total']['creditor_balance'][$row['currency_code']])) {
                    $data['total']['creditor_balance'][$row['currency_code']] = 0;
                }
                $data['total']['creditor_balance'][$row['currency_code']] += $row['creditor_balance'];
            }
        }
        return $data;
    }
    
    public function getDataCreditorRevenue(array $filters = array())
    {
        $main = "
            SELECT 
                (cr.lastname::text || ' '::text || cr.firstname::text) AS creditor_fullname, 
                c.currency_code as currency_code,
                creditor_received_payments(cr.id, '%date_to%'::date) as creditor_received_payments,
                sum(contract_balance(c.id, '%date_to%'::date))::integer AS creditor_current_balance,
                sum(contract_balance(c.id, '%date_to%'::date)) - creditor_received_payments(cr.id, '%date_to%'::date) as creditor_balance_change,
                sum(contract_capitalized(c.id, '%date_to%'::date))::integer AS creditor_capitalized,
                sum(contract_balance_reduction(c.id, '%date_to%'::date))::integer AS creditor_balance_reduction,
                sum(contract_interest_regular(c.id, '%date_to%'::date))::integer AS creditor_interest_regular,
                sum(contract_paid(c.id, '%date_to%'::date))::integer AS creditor_paid,
                sum(contract_unpaid_regular(c.id, '%date_to%'::date))::integer AS creditor_unpaid_regular
            FROM creditor cr
            JOIN contract c ON c.creditor_id = cr.id
            GROUP BY  currency_code, cr.id, cr.lastname, cr.firstname
            ORDER BY %order_by%
            ;";

        $replacements = array(
            '%order_by%' => 'creditor_fullname',
            '%date_to%' => $filters['date_to'],
        );

        $columns = array(
            'creditor_received_payments' => 1,
            'creditor_current_balance' => 1,
            'creditor_balance_change' => 1,
            'creditor_capitalized' => 1,
            'creditor_balance_reduction' => 1,
            'creditor_interest_regular' => 1,
            'creditor_paid' => 1,
            'creditor_unpaid_regular' => 1,
        );
        $orderBy = $this->getOrderBy($filters, array_merge(array('creditor_fullname'), array_keys($columns)));
        if ($orderBy) {
            $replacements['%order_by%'] = $orderBy;
        }
        $replacements = $this->getReplacements($replacements, $filters);
        $rows = $this->procesQueryFetchAll($main, $replacements);

        $data = array(
            'total' => array(),
            'rows' => $rows,
            'currency_codes' => array(),
            'columns' => $columns
        );
        foreach ($columns as $column => $isCurrency) {
            foreach ($rows as $row) {
                $data['currency_codes'][$row['currency_code']] = $row['currency_code'];
                if (!isset($data['total'][$column][$row['currency_code']])) {
                    $data['total'][$column][$row['currency_code']] = 0;
                }
                $data['total'][$column][$row['currency_code']] += $row[$column];
            }
        }
        return $data;
    }

    public function getDataBirthday(array $filters = array())
    {
        $main = "
            SELECT 
                cr.id as creditor_id,
                (cr.lastname::text || ' '::text || cr.firstname::text) AS creditor_fullname,
                cr.street || ', ' || cr.city || ', ' || cr.zip as creditor_address,
                (date_part('year', now()) || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date as creditor_birthday,
                
                CASE (date_part('year', now()) || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date > now()::date
                WHEN true
                THEN
                    (date_part('year', now()) || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date 
                ELSE 
                    (date_part('year', now())+1 || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date 
                END  as creditor_next_birthday,
                CASE (date_part('year', now()) || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date > now()::date
                WHEN true
                THEN
                    (SELECT EXTRACT(year from AGE((date_part('year', now()) || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date, cr.birth_date)))
                ELSE 
                    (SELECT EXTRACT(year from AGE((date_part('year', now())+1 || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date, cr.birth_date)))
                END as creditor_age
            FROM creditor cr
            ORDER BY %order_by%
            ;";

        $replacements = array(
            '%order_by%' => 'creditor_fullname',
        );

        $columns = array(
            'creditor_address' => 0,
            'creditor_next_birthday' => 0,
            'creditor_age' => 0,
        );
        $orderBy = $this->getOrderBy($filters, array_merge(array('creditor_fullname'), array_keys($columns)));
        if ($orderBy) {
            $replacements['%order_by%'] = $orderBy;
        }
        $replacements = $this->getReplacements($replacements, $filters);
        $rows = $this->procesQueryFetchAll($main, $replacements);

        $data = array(
            'rows' => $rows,
            'columns' => $columns
        );

        return $data;
    }

    protected function procesQueryFetchAll($query, array $replacements = array())
    {
        $query = str_replace(array_keys($replacements), $replacements, $query);
        $connection = Propel::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $reportType
     * @return ReportForm $reportForm
     */
    public function getForm($reportType)
    {
        $defaultReportForm = 'ReportForm';
        $formClass = sfInflector::camelize($reportType) . $defaultReportForm;
        if (!class_exists(($formClass))) {
            $formClass = $defaultReportForm;
        }
        $form = new $formClass;

        return $form;
    }

    protected function getReplacements(array $replacements = array(), array $filters = array(), array $whereConditionsDefinitions = array())
    {
        $whereConditions = $this->getWhereConditions($whereConditionsDefinitions, $filters);
        $whereConditionsAsString = '';
        if (count($whereConditions)) {
            $whereConditionsAsString = ' WHERE ' . implode(' AND ', $whereConditions);
        }
        $replacements['%where_conditions%'] = $whereConditionsAsString;
        return $replacements;
    }

    protected function getOrderBy(array $filters = array(), array $columns = array())
    {
        $orderBy = null;
        $orderByColumn = null;
        if (array_key_exists('sort', $filters) && $filters['sort']) {
            $orderByColumn = $orderBy = $filters['sort'];

            if (array_key_exists('sort', $filters) && $filters['sort_type']) {
                $orderBy .= ' ' . $filters['sort_type'];
            }
        }
        if (!in_array($orderByColumn, $columns)) {
            $orderBy = null;
        }

        return $orderBy;
    }

    protected function getWhereConditions(array $whereConditionsDefinitions = array(), array $filters = array())
    {
        $quotationedColumns = array(
            'date_from',
            'date_to',
        );
        $whereConditions = array();
        foreach ($whereConditionsDefinitions as $filter => $condition) {
            if (array_key_exists($filter, $filters) && $filters[$filter]) {
                $conditionValue = $filters[$filter];
                $quotation = in_array($filter, $quotationedColumns) ? "'" : "";

                $whereConditions[] = sprintf(' %s %s%s%s', $condition, $quotation, $conditionValue, $quotation);
            }
        }

        return $whereConditions;
    }

}
