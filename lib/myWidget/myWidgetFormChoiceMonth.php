<?php

class myWidgetFormChoiceMonth extends myWidgetFormChoice
{
    public function getChoices()
    {
        $i18n = sfContext::getInstance()->getI18N();

        $months = array();

        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $i18n->__(date("F", mktime(0, 0, 0, $i, 10)));
        }

        return $months;
    }
}