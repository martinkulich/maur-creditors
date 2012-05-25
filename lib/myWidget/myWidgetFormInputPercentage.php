<?php

class myWidgetFormInputPercentage extends sfWidgetFormInput
{

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $attributes['class'] = 'span1';
        $render = parent::render($name, $value, $attributes, $errors);
        $pattern = '<div class="input-prepend input-append"></span>%s<span class="add-on">%%</span></div>';
        $i18n = sfContext::getInstance()->getI18N();
        return sprintf($pattern, $render);
    }
}