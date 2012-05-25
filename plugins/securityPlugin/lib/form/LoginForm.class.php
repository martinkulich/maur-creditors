<?php
class LoginForm extends sfForm
{
    public function setup()
    {
        $this->setWidget('email', new sfWidgetFormInput());
        $this->setWidget('password', new sfWidgetFormInputPassword());
        $this->setValidator('email', new sfValidatorEmail(array('required'=>true)));
        $this->setValidator('password', new sfValidatorString(array('required'=>true)));

        $this->disableCSRFProtection();
        
        $this->widgetSchema->setNameFormat('login[%s]');
    }
    
    public function configure()
    {
    
    }
}