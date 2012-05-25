<?php

/**
 * Base project form.
 * 
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class myMailer extends sfMailer
{

    public function compose($from = null, $to = null, $subject = null, $body = null)
    {
        return Swift_Message::newInstance()
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html')
        ;
    }
    
    public function conposeAndSendToMultipeSeparatedRecipients($from, $to, $subject, $body)
    {
        $message = Swift_Message::newInstance()
            ->setFrom($from)
            ->setBcc($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html');
        return $this->send($message);
    }

}
