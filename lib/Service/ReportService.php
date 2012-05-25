<?php

class ReportService
{

    public function getData($reportType, array $filters = array())
    {
        $dataFunction = 'getData' . sfInflector::camelize($reportType);

        return $this->$dataFunction($filters);
    }

    public function getDataReservations(array $filters = array())
    {
        $pattern = "
            SELECT
                COALESCE(u.surname || ' ' || u.firstname, r.user_string)  as user_fullname,
                r.id,
                r.date,
                COALESCE(r.paid, false) as paid,
                pc.name as price_category,
                s.name as sport,
                COALESCE(sum(rtz.amount),0) as amount,
                COALESCE(sum(rtz.sale_amount),0) as sale,
                COALESCE(sum(rtz.amount),0) + COALESCE(sum(rtz.sale_amount),0)  as total_amount
            FROM reservation_time_zone rtz
            JOIN time_zone tz ON tz.id= rtz.time_zone_id
            JOIN reservation r ON R.id = rtz.reservation_id
            JOIN reservation_curt rc on r.id = rc.reservation_id
            JOIN schedule sch ON sch.id = r.schedule_id AND sch.playground_id = %playground_id%
            JOIN sport s ON r.sport_id = s.id
            JOIN price_category pc ON r.price_category_id = pc.id
            %user_join_type% JOIN security_user u ON r.user_id = u.id
            %where_conditions%
            group by r.id, r.date, user_fullname, sport, paid, price_category
            ORDER BY %order_by%
        ";

        $replacements = array(
            '%user_join_type%' => ' LEFT ',
            '%playground_id%' => ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId(),
            '%order_by%' => 'r.date',
        );
        $whereConditions = array();
        if (array_key_exists('date_from', $filters) && $filters['date_from']) {
            $replacements['%date_from%'] = $filters['date_from'];
            $whereConditions[] = "r.date >= '%date_from%'";
        }

        if (array_key_exists('date_to', $filters) && $filters['date_to']) {
            $replacements['%date_to%'] = $filters['date_to'];
            $whereConditions[] = "r.date <= '%date_to%'";
        }

        if (array_key_exists('sport_id', $filters) && $filters['sport_id']) {
            $replacements['%sport_id%'] = $filters['sport_id'];
            $whereConditions[] = "r.sport_id = %sport_id%";
        }

        if (array_key_exists('price_category_id', $filters) && $filters['price_category_id']) {
            $replacements['%price_category_id%'] = $filters['price_category_id'];
            $whereConditions[] = "r.price_category_id = %price_category_id%";
        }

        if (array_key_exists('paid', $filters) && !is_null($filters['paid'])) {
            if ($filters['paid'] == 1) {
                $whereConditions[] = "r.paid = true";
            } else {
                $whereConditions[] = "(r.paid = false OR r.paid IS NULL)";
            }
        }

        if (array_key_exists('user_id', $filters) && $filters['user_id']) {
            $replacements['%user_id%'] = $filters['user_id'];
            $replacements['%user_join_type%'] = '';
            $whereConditions[] = "r.user_id =%user_id%";
        }

        if (array_key_exists('sort', $filters) && $filters['sort']) {
            $orderBy = $filters['sort'];

            if(array_key_exists('sort', $filters) && $filters['sort_type'])
            {
                $orderBy .= ' '.$filters['sort_type'];
            }
            $replacements['%order_by%'] = $orderBy;
        }


        $whereConditionString = '';
        if (count($whereConditions)) {
            $whereConditionString = ' WHERE ' . implode(' AND ', $whereConditions);
        }

        $query = str_replace('%where_conditions%', $whereConditionString, $pattern);
        $rows = $this->procesQueryFetchAll($query, $replacements);
        $data = array('rows', 'total' => array('amount' => 0, 'sale' => 0, 'total_amount' => 0));
        foreach ($rows as $key => $row) {
            $data['rows'][$row['id']] = $row;
            $data['total']['amount'] += $row['amount'];
            $data['total']['sale'] += $row['sale'];
            $data['total']['total_amount'] += $row['total_amount'];
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
}
