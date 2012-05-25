<?php

class CalendarForm extends sfForm
{
    public function configure($options = array (), $attributes = array ())
    {
        $this->setWidgets(array(
           'date'=> new myJQueryDateWidget(array('type'=>'hidden', 'date_format'=>'yy-mm-dd'), array('onchange'=>"submit()", 'size'=>7)),
        ));

        $this->setValidators(array(
           'date'=> new myValidatorDate(array('required'=>true)),
        ));


        $this->disableCSRFProtection();
    }
}