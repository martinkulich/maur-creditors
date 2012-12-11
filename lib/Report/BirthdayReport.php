<?php

class BirthdayReport extends Report
{

    public function getSqlPatter()
    {
        return "
            SELECT 
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
            ORDER BY %order_by%
            ;
        ";
    }

    public function getColumns()
    {
        return array(
            'fullname',
            'address',
            'birthday',
            'age'
        );
    }

    public function getDateColumns()
    {
        return array('birthday');
    }

    public function getColumnRowClass($column)
    {
        $class = parent::getColumnRowClass($column);
        ;
        if ($column == 'age') {
            $class = static::ALIGN_RIGHT;
        }
        return $class;
    }

}
