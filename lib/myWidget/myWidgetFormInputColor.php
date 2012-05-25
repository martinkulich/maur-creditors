<?php

class myWidgetFormInputColor extends sfWidgetFormInput
{

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $this->setOption('type', 'hidden');
    }

    public function getJavaScripts()
    {
        $scripts = parent::getJavaScripts();

        $path = '/farbtastic/';
        $scripts[] = $path . 'farbtastic.js';
        return $scripts;
    }

    public function getStylesheets()
    {
        $sheets = parent::getStylesheets();

        $path = '/farbtastic/';
        $sheets[$path . 'farbtastic'] = 'screen';
        //$sheets[$path.'layout.css'] = 'screen';
        return $sheets;
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $id = $this->generateId($name);
        $tag = $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));


        $function = javascript_tag(
                "jQuery(document).ready(function()
            {
                jQuery('#demo').hide();
                jQuery('#picker_" . $id . "').farbtastic('#" . $id . "');
            });"
        );
        $div = '<div id="picker_' . $id . '"></div>';

        return $function . $tag . $div;
    }

}