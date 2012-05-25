<?php

class myValidatorMultipleEmail extends sfValidatorEmail
{
    const SEPARATOR = ',';

    protected function configure($options = array(), $messages = array())
    {
        $this->addOption('separator', self::SEPARATOR);

        $this->addMessage('invalid_email', 'Email %email% is invalid');
        parent::configure($options, $messages);
    }

    public function doClean($value)
    {
        $separator = self::SEPARATOR;
        $emails = explode($separator, $value);
        foreach ($emails as $email) {
            try {
                $result = parent::doClean(trim($email));
            } catch (Exception $exc) {
                throw new sfValidatorError($this, 'invalid_email', array('email' => $email));
            }
        }

        return $value;
    }

}