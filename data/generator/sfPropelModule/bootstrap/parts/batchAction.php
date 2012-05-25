  public function executeBatch(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    if (!$ids = $request->getParameter('ids'))
    {
      $error='You must at least select one item.';
      ServiceContainer::getMessageService()->addError($error);

      return $this->redirect('@<?php echo $this->getUrlForAction('list') ?>', 205);
    }

    if (!$action = $request->getParameter('batch_action'))
    {
      $error='You must select an action to execute on the selected items.';
      ServiceContainer::getMessageService()->addError($error);

      return $this->redirect('@<?php echo $this->getUrlForAction('list') ?>', 205);
    }

    if (!method_exists($this, $method = 'execute'.ucfirst($action)))
    {
      throw new InvalidArgumentException(sprintf('You must create a "%s" method for action "%s"', $method, $action));
    }

    if (!$this->getUser()->hasCredential($this->configuration->getCredentials($action)))
    {
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    $validator = new sfValidatorPropelChoice(array('multiple' => true, 'model' => '<?php echo $this->getModelClass() ?>'));
    try
    {
      // validate ids
      $ids = $validator->clean($ids);

      // execute batch
      $this->$method($request);
    }
    catch (sfValidatorError $e)
    {
      $error='A problem occurs when deleting the selected items as some items do not exist anymore.';
      ServiceContainer::getMessageService()->addError($error);
    }

    return $this->redirect('@<?php echo $this->getUrlForAction('list') ?>', 205);
  }

  protected function executeBatchDelete(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');

    $count = 0;
    foreach (<?php echo constant($this->getModelClass().'::PEER') ?>::retrieveByPks($ids) as $object)
    {
      $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $object)));

      $object->delete();
      if ($object->isDeleted())
      {
        $count++;
      }
    }

    if ($count >= count($ids))
    {
      $notice='The selected items have been deleted successfully.';
      ServiceContainer::getMessageService()->addSuccess($notice);
    }
    else
    {
      $error='A problem occurs when deleting the selected items.';
      ServiceContainer::getMessageService()->addError($error);
    }

    $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }
