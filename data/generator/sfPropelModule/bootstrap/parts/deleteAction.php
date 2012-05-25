  public function executeDelete(sfWebRequest $request)
  {
    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));

    $this->getRoute()->getObject()->delete();

    $notice='The item was deleted successfully';
    ServiceContainer::getMessageService()->addSuccess($notice);

    $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }
