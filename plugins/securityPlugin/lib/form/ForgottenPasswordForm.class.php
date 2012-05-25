<?php
class ForgottenPasswordForm extends sfForm
{
    public function setup()
    {
        $this->setWidget('email', new sfWidgetFormInput());
        $this->setValidator('email', new sfValidatorEmail(array('required'=>true)));
        $this->widgetSchema->setNameFormat('forgotten_password[%s]');

        $this->disableCSRFProtection();
    }

    public function configure()
    {

    }
}