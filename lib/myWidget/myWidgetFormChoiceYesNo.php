<?php

class myWidgetFormChoiceYesNo extends sfWidgetFormChoice
{

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $this->setOption('choices', $this->getChoices());
        $this->setAttribute('class', 'span2');
    }

    public function getChoices()
    {
        $i18n = sfContext::getInstance()->getI18N();
        return array(
            '' => $i18n->__('yes or no'),
            1 => $i18n->__('yes'),
            0 => $i18n->__('no')
        );
    }
}