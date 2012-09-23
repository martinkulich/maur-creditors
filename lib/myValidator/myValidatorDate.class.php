<?php

class myValidatorDate extends sfValidatorDate
{

    public function configure($options = array(), $messages = array())
    {
        $this->addOption('last_day_in_month', 30);
        $this->addMessage('last_day_in_month_error', 'Last allowed day in month is %last_day_in_month%');
        parent::configure($options, $messages);
    }

    public function doClean($value)
    {

        if (is_array($value) && array_key_exists('date', $value) && !is_array($value['date'])) {
            $date = new DateTime($value['date']);
            $value['year'] = $date->format('Y');
            $value['month'] = $date->format('m');
            $value['day'] = $date->format('d');

            $lastDayInMonth = $this->getOption('last_day_in_month');
            if ($value['day'] > $lastDayInMonth) {
                throw new sfValidatorError($this, 'last_day_in_month_error', array('last_day_in_month' => $lastDayInMonth));
            }
        }
        $value = parent::doClean($value);

        return $value;
    }
}