<?php

class myValidatorDate extends sfValidatorDate
{

    public function doClean($value)
    {
        if(is_array($value) && array_key_exists('date', $value) && !is_array($value['date']))
        {
            $date = new DateTime($value['date']);
            $value['year'] = $date->format('Y');
            $value['month'] = $date->format('m');
            $value['day'] = $date->format('d');
        }
        $value = parent::doClean($value);

        return $value;
    }

}