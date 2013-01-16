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
                END as age,
                (select \"date\" from gift where creditor_id = cr.id order by id desc limit 1) as last_gift_date,
                null as add_gift

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
            'age',
            'last_gift_date',
            'add_gift',
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
        elseif ($column == 'add_gift') {
            $class = static::ALIGN_CENTER;
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

    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);


        if($column == 'last_gift_date' && $formatedValue)
        {
            $formatedValue = link_to(format_date($formatedValue, 'D'), '@creditor_giftList?id='.$row['creditor_id'], array('class'=>'modal_link'));
        }
        elseif($column == 'add_gift')
        {
            $formatedValue = link_to('Přidat dárek', '@creditor_addGift?id='.$row['creditor_id'], array('class'=>'modal_link'));
        }


        return $formatedValue;
    }

}
