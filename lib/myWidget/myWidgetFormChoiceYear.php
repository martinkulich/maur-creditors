<?php

class myWidgetFormChoiceYear extends myWidgetFormChoice
{

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $currentYear = date('Y');
        $this->addOption('min_year', $currentYear - 5);
        $this->addOption('max_year', $currentYear + 1);
    }

    public function getChoices()
    {
        $i18n = sfContext::getInstance()->getI18N();

        $years = parent::getChoices();

        for ($i = $this->getOption('min_year'); $i <= $this->getOption('max_year'); $i++) {
            $years[$i] = $i;
        }

        return $years;
    }

}