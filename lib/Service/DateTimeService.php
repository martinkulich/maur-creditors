<?php

class DateTimeService
{
    protected $weekDays = null;

    /**
     * @return Currency
     */
    public function getWeekDays()
    {
        if (is_null($this->weekDays)) {
            $translateService = ServiceContainer::getTranslateService();
            $this->weekDays = array();
            $day = new DateTime('2010-11-21');
            for($i = 1; $i <= 7; $i++)
            {
                $day->modify('+1 day');;
                $this->weekDays[$i] = array(
                    'long' => $translateService->__($day->format('l')),
                    'short' => $translateService->__($day->format('D')),
                );
            }

        }

        return $this->weekDays;
    }

    public function getWeekDayNumber(DateTime $date)
    {
        return $date->format('N');
    }

}
