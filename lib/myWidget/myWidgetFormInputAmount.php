<?php

class myWidgetFormInputAmount extends myWidgetFormInputNumber
{

    protected function configure($options = array(), $attributes = array())
    {
        $this->addOption('currency_code', 'CZK');
        parent::configure($options, $attributes);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $render = parent::render($name, $value, $attributes, $errors);
        $pattern = '<div class="input-prepend input-append"></span>%s<span class="add-on">%s</span></div>';
        $i18n = sfContext::getInstance()->getI18N();
        return sprintf($pattern, $render, $i18n->__($this->getOption('currency_code')));
    }

}