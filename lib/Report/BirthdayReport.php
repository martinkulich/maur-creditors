<?php

class BirthdayReport extends Report
{

    public function getSqlPatter()
    {
        return "
            SELECT 
                cr.id as creditor_id,
                (cr.lastname::text || ' '::text || cr.firstname::text) as fullname,
                cr.street || ', ' || cr.city || ', ' || cr.zip as address,
                (date_part('year', now()) || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date as birthday,
                
                CASE (date_part('year', now()) || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date > now()::date
                WHEN true
                THEN
                    (date_part('year', now()) || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date 
                ELSE 
                    (date_part('year', now())+1 || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date 
                END  as next_birthday,
                CASE (date_part('year', now()) || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date > now()::date
                WHEN true
                THEN
                    (SELECT EXTRACT(year from AGE((date_part('year', now()) || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date, cr.birth_date)))
                ELSE 
                    (SELECT EXTRACT(year from AGE((date_part('year', now())+1 || '-' ||date_part('month', cr.birth_date)|| '-' ||date_part('day', cr.birth_date))::date, cr.birth_date)))
                END as age
            FROM creditor cr
            %where%
            ORDER BY %order_by%
            ;
        ";
    }

    public function getColumns()
    {
        return array(
            'fullname',
            'address',
            'next_birthday',
            'age'
        );
    }

    public function getDateColumns()
    {
        return array('next_birthday');
    }

    public function getColumnRowClass($column, array $row = array())
    {
        $class = parent::getColumnRowClass($column);
        ;
        if ($column == 'age') {
            $class = static::ALIGN_RIGHT;
        }
        return $class;
    }
    
    public function getWhere()
    {
        $where = '';
        if ($creditorId = $this->getFilter('creditor_id')) {
            $where = ' WHERE cr.id = ' . $creditorId;
        }
        return $where;
    }
    
    protected function getDefaultOrderBy()
    {
        return 'next_birthday';
    }

}
