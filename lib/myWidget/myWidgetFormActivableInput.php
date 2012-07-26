<?php

class myWidgetFormActivableInput extends sfWidgetFormInput
{

    public function configure($options = array(), $attributes = array())
    {
        $this->addOption('checkbox_widget');
        $this->addOption('checkbox_widget_render_name');
        $this->addOption('checked', false);
        $this->addOption('on_uncheck');
        parent::configure($options, $attributes);
    }
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $id = $this->generateId($name);
        $checkboxName = $this->getOption('checkbox_widget_render_name');
        $checkboxId = $this->generateId($checkboxName);
        $functionName = sfInflector::camelize($checkboxId . "ToogleStatus");
        $javascript = javascript_tag(
            "
            $(document).ready(function(){
                %function_name%();
            });

            function %function_name%() {
                if ($('%toogle_selector%').is(':checked')) {
                    $('%element_to_operate_selector%').removeAttr('readonly');
                } else {
                    $('%element_to_operate_selector%').attr('readonly', true);
                    %on_uncheck%
                }
            }
            ");
        $javascriptReplacements = array(
            '%function_name%'=> $functionName,
            '%toogle_selector%'=>'#'.$checkboxId,
            '%element_to_operate_selector%'=>'#'.$id,
            '%on_uncheck%'=>$this->hasOption('on_uncheck') ? $this->getOption('on_uncheck') : '',
        );

        $javascript = str_replace(array_keys($javascriptReplacements), $javascriptReplacements,  $javascript);
        $render = parent::render($name, $value, $attributes, $errors);
        $pattern = '<div class="input-prepend input-append"><span class="add-on">%checkbox%</span>%input%</div>%javascript%';


        $checkbox = $this->getOption('checkbox_widget');
        $checkbox->setAttribute('onChange', $functionName.'();');
        $checkboxRender = $checkbox->render($checkboxName, $this->getOption('checked'));
        $replacements = array(
            '%javascript%' => $javascript,
            '%input%'=> $render,
            '%checkbox%'=>$checkboxRender,
        );
        return str_replace(array_keys($replacements), $replacements, $pattern);
    }
}