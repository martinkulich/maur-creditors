<?php

class myWidgetFormChoiceColor extends sfWidgetFormChoice
{

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $this->setOption('expanded', 'true');
        $this->setOption('choices', $this->getChoices());
    }

    public function getChoices()
    {
        $i18n = sfContext::getInstance()->getI18N();
        return array(
            'red' => $i18n->__('Red'),
            'orange'=>$i18n->__('Orange'),
            'green'=>$i18n->__('Green'),
            'blue' => $i18n->__('Blue'),
            'light-blue'=>$i18n->__('Light blue'),
        );
    }
}