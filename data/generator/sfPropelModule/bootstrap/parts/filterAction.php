  public function executeReset(sfWebRequest $request)
  {
      $this->setPage(1);

      $this->setFilters($this->configuration->getFilterDefaults());

      return $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }

  public function executeFilter(sfWebRequest $request)
  {

    $this->form = $this->configuration->getFilterForm($this->getFilters());

    $this->form->bind($request->getParameter($this->form->getName()));
    if ($this->form->isValid())
    {
      $this->setFilters($this->form->getValues());

      $code = $request->getParameter('modeless', false) ? 302 : 205;
      return $this->redirect('@<?php echo $this->getUrlForAction('list') ?>', $code);
    }
    else
    {
        ServiceContainer::getMessageService()->addFromErrors($this->form);
    }

    $this->pager = $this->getPager();
    $this->sort = $this->getSort();

    $this->setTemplate('filters');
  }
