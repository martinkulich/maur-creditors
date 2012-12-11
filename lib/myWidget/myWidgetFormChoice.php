<?php

class myWidgetFormChoice extends sfWidgetFormChoice
{

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $this->setOption('choices', $this->getChoices());
    }

    public function getChoices()
    {
        $i18n = sfContext::getInstance()->getI18N();

        $months = array();

        for ($i = 0; $i <= 12; $i++) {
            $months[$i] = $i18n->__(date("F", mktime(0, 0, 0, $i, 10)));
        }

        return $months;
    }
    
    public  function getChoicesKeys()
    {
        $choices = $this->getChoices();
        
        return array_keys($choices);
    }

}