<?php

class ContactForm extends MailForm
{
    public function configure($options = array (), $attributes = array ())
    {
        parent::configure();

        $this->setWidget('recipients', new sfWidgetFormInputHidden());
        $this->setWidget('sender', new sfWidgetFormInputHidden());
    }
}