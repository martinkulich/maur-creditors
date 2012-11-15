<?php

class myWidgetFormInputNumber extends sfWidgetFormInput
{

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $render =  parent::render($name, $value, $attributes, $errors);
        return $render .= $this->renderJavascript($name);
    }
    
    protected function renderJavascript($name)
    {
        $id = $this->generateId($name);
        return 
        "<script type='text/javascript'>
            $(document).ready(function(){
                ".$id."Mask = new Mask('#,###', 'number');
                ".$id."Mask.attach(document.formMask.$id);
                jQuery('#$id').blur();
            });
            
        </script>"
        ;
    }

}