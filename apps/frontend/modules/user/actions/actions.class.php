<?php

require_once dirname(__FILE__) . '/../lib/userGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/userGeneratorHelper.class.php';

/**
 * user actions.
 *
 * @package    rezervuj
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class userActions extends autoUserActions
{

    public function executeBatch(sfWebRequest $request)
    {
        $request->checkCSRFProtection();

        if (!$ids = $request->getParameter('ids')) {
            $error = 'You must at least select one item.';
            ServiceContainer::getMessageService()->addError($error);

            return $this->redirect('@user', 205);
        }

        if (!$action = $request->getParameter('batch_action')) {
            $error = 'You must select an action to execute on the selected items.';
            ServiceContainer::getMessageService()->addError($error);

            return $this->redirect('@user', 205);
        }

        if (!method_exists($this, $method = 'execute' . ucfirst($action))) {
            throw new InvalidArgumentException(sprintf('You must create a "%s" method for action "%s"', $method, $action));
        }

        if (!$this->getUser()->hasCredential($this->configuration->getCredentials($action))) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }

        $validator = new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'securityUser'));
        try {
            // validate ids
            $ids = $validator->clean($ids);

        } catch (sfValidatorError $e) {
            $error = 'A problem occurs when deleting the selected items as some items do not exist anymore.';
            ServiceContainer::getMessageService()->addError($error);
        }

        return $this->$method($request);
//        return $this->redirect('@user', 205);
    }

    public function executeBatchSend_mail(sfWebRequest $request)
    {
//        return $this->redirect('@mail_new');
        return $this->forward('mail', 'new');
    }

    protected function processForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully' : 'The item was updated successfully';

            $securityUser = $form->save();

            $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $securityUser)));


            ServiceContainer::getMessageService()->addSuccess($notice);

            $this->redirect(array('sf_route' => 'user'), 205);
        } else {
            ServiceContainer::getMessageService()->addFromErrors($form);
        }
    }
}
