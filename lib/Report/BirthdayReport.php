<?php

class BirthdayReport extends Report
{

    public function getSqlPatter()
    {
        return "
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
            ;
        ";
    }

    public function getColumns()
    {
        return array(
            'creditor_fullname',
            'creditor_address',
            'creditor_birthday',
            'creditor_age'
        );
    }

    public function getDateColumns()
    {
        return array('creditor_birthday');
    }

    public function getColumnRowClass($column)
    {
        $class = parent::getColumnRowClass($column);
        ;
        if ($column == 'creditor_age') {
            $class = static::ALIGN_RIGHT;
        }
        return $class;
    }

}
