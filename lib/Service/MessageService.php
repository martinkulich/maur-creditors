<?php

class MessageService
{
    private $messages = null;
    private $messageTypes = array('error', 'warning', 'info', 'success');

    const NAMESPACE_NAME = 'messages';

    public function addMessage($messageType, $message)
    {
        if (!in_array($messageType, $this->messageTypes)) {
            throw new Exception(sprintf('Unknow message type: %s', $messageType));
        }

        $this->messages[$messageType] = $this->getMessagesByType($messageType, false);
        $this->messages[$messageType][] = $message;
        sfContext::getInstance()->getUser()->setAttribute($messageType, $this->messages[$messageType], self::NAMESPACE_NAME);
    }

    public function addError($error)
    {
        $this->addMessage('error', $error);
    }

    public function addSuccess($message)
    {
        $this->addMessage('success', $message);
    }

    public function addWarning($warning)
    {
        $this->addMessage('warning', $warning);
    }

    public function getAllMessages($clear = true)
    {
        $messages = array();
        foreach ($this->messageTypes as $messageType) {
            $messages[$messageType] = $this->getMessagesByType($messageType, $clear);
        }
        return $messages;
    }

    public function getMessagesByType($mesageType, $clear = true)
    {
        $user = sfContext::getInstance()->getUser();
        $messages = $user->getAttribute($mesageType, array(), self::NAMESPACE_NAME);
        if ($clear) {
            $user->setAttribute($mesageType, array(), self::NAMESPACE_NAME);
        }
        return $messages;
    }

    public function addFromErrors(sfForm $form, $debug=false)
    {
        $i18n = sfContext::getInstance()->getI18N();
        foreach ($form->getErrorSchema()->getGlobalErrors() as $error) {
            $this->addError($i18n->__($error->getMessageFormat(), $error->getArguments()));
        }
        foreach ($form->getEmbeddedForms() as $embeddedForm) {
            $this->addFromErrors($embeddedForm);
        }

        if ($debug) {
            foreach ($form->getErrorSchema()->getErrors() as $field=> $error) {
                $this->addError($field.': '.$i18n->__($error->getMessageFormat(), $error->getArguments()));
            }
        }
    }
}
