<?php

class myWidgetFormChoice extends sfWidgetFormChoice
{

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $this->setOption('choices', $this->getChoices());
        $this->addOption('add_empty');
    }

    public function getChoices()
    {
        $choices = array();
        
        if($this->getOption('add_empty') == true)
        {
            $choices[''] = '';
        }
        
        return $choices;
    }
    
    public  function getChoicesKeys()
    {
        $choices = $this->getChoices();
        
        return array_keys($choices);
    }

}