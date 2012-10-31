<?php

class ReportService
{

    public function getData($reportType, array $filters = array())
    {
        $dataFunction = 'getData' . sfInflector::camelize($reportType);

        return $this->$dataFunction($filters);
    }

    public function getDataUnpaid(array $filters = array(), $price = false)
    {
        $main = "
            SELECT 
                cr.id as creditor_id,
                c.currency_code as currency_code,
                (cr.lastname::text || ' '::text || cr.firstname::text) AS creditor_fullname, 
                sum(contract_unpaid(c.id, '%date_to%'))::integer AS creditor_unpaid
            FROM creditor cr
            JOIN contract c ON cr.id = c.creditor_id
            GROUP BY c.currency_code, cr.id, cr.lastname, cr.firsname
            HAVING sum(contract_unpaid(c.id, '%date_to%'))::integer <> 0
            ORDER BY %order_by%
            ;";
        $replacements = array(
            '%order_by%' => 'creditor_fullname',
            '%date_to%' => $filters['date_to'],
        );

        $replacements = $this->getReplacements($replacements, $filters);
        $rows = $this->procesQueryFetchAll($main, $replacements);
        $data = array(
            'total' => array(),
            'rows' => $rows,
            'currency_codes'=>array(),
            'columns' => array(
                'creditor_unpaid' => 1,
            ),
        );

        foreach ($rows as $row) {
            
            $data['currency_codes'][$row['currency_code']] = $row['currency_code'];
            if ($row['creditor_unpaid']) {
                if (!isset($data['total']['creditor_unpaid'][$row['currency_code']])) {
                    $data['total']['creditor_unpaid'][$row['currency_code']] = 0;
                }
                $data['total']['creditor_unpaid'][$row['currency_code']] += $row['creditor_unpaid'];
            }
        }
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
        $formClass = sfInflector::camelize($reportType) . 'ReportForm';

        $form = new $formClass;

        return $form;
    }

    protected function getReplacements(array $replacements = array(), array $filters = array(), array $whereConditionsDefinitions = array())
    {
        $orderBy = $this->getOrderBy($filters);
        if ($orderBy) {
            $replacements['%order_by%'] = $orderBy;
        }
        $whereConditions = $this->getWhereConditions($whereConditionsDefinitions, $filters);
        $whereConditionsAsString = '';
        if (count($whereConditions)) {
            $whereConditionsAsString = ' WHERE ' . implode(' AND ', $whereConditions);
        }
        $replacements['%where_conditions%'] = $whereConditionsAsString;
        return $replacements;
    }

    protected function getOrderBy(array $filters = array())
    {
        $orderBy = null;
        if (array_key_exists('sort', $filters) && $filters['sort']) {
            $orderBy = $filters['sort'];

            if (array_key_exists('sort', $filters) && $filters['sort_type']) {
                $orderBy .= ' ' . $filters['sort_type'];
            }
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
