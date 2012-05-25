<?php

require_once(dirname(__FILE__).'/../lib/BaseRegulationGeneratorConfiguration.class.php');
require_once(dirname(__FILE__).'/../lib/BaseRegulationGeneratorHelper.class.php');

/**
 * regulation actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage regulation
 * @author     ##AUTHOR_NAME##
 */
abstract class autoRegulationActions extends sfActions
{
  public function preExecute()
  {
    $this->configuration = new regulationGeneratorConfiguration();

    if (!$this->getUser()->hasCredential($this->configuration->getCredentials($this->getActionName())))
    {
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    $this->dispatcher->notify(new sfEvent($this, 'admin.pre_execute', array('configuration' => $this->configuration)));

    $this->helper = new regulationGeneratorHelper();

    parent::preExecute();
  }

  public function executeIndex(sfWebRequest $request)
  {
    // sorting
    if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort')))
    {
      $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
    }

    // pager
    if ($request->getParameter('page'))
    {
      $this->setPage($request->getParameter('page'));
    }

    $this->pager = $this->getPager();
    $this->sort = $this->getSort();
    $this->showResetFilter = count($this->getFilters()) > 0;
  }

  public function executeReset(sfWebRequest $request)
  {
      $this->setPage(1);

      $this->setFilters($this->configuration->getFilterDefaults());

      return $this->redirect('@regulation');
  }

  public function executeFilter(sfWebRequest $request)
  {

    $this->form = $this->configuration->getFilterForm($this->getFilters());

    $this->form->bind($request->getParameter($this->form->getName()));
    if ($this->form->isValid())
    {
      $this->setFilters($this->form->getValues());

      return $this->redirect('@regulation', 205);
    }
    else
    {
        ServiceContainer::getMessageService()->addFromErrors($this->form);
    }

    $this->pager = $this->getPager();
    $this->sort = $this->getSort();

    $this->setTemplate('filters');
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = $this->configuration->getForm();
    $this->regulation = $this->form->getObject();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = $this->configuration->getForm();
    $this->regulation = $this->form->getObject();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->regulation = $this->getRoute()->getObject();
    $this->form = $this->configuration->getForm($this->regulation);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->regulation = $this->getRoute()->getObject();
    $this->form = $this->configuration->getForm($this->regulation);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));

    $this->getRoute()->getObject()->delete();

    $notice='The item was deleted successfully';
    ServiceContainer::getMessageService()->addSuccess($notice);

    $this->redirect('@regulation');
  }


  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $notice = $form->getObject()->isNew() ? 'The item was created successfully' : 'The item was updated successfully';

      $regulation = $form->save();

      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $regulation)));

      $redirect = array('sf_route' => 'regulation_edit', 'sf_subject' => $regulation);

      if ($request->hasParameter('_save_and_add'))
      {
        $notice .=' You can add another one below.';
        $redirect = '@regulation_new';
      }

      ServiceContainer::getMessageService()->addSuccess($notice);

      return $this->redirect($redirect, 205);
    }
    else
    {
        ServiceContainer::getMessageService()->addFromErrors($form);
    }
  }

  public function executeFilters(sfWebRequest $request)
  {
    $this->form = $this->configuration->getFilterForm($this->getFilters());

    $this->form->bind($this->getFilters());
  }

  protected function getFilters()
  {
    return $this->getUser()->getAttribute('regulation.filters', $this->configuration->getFilterDefaults(), 'admin_module');
  }

  protected function setFilters(array $filters)
  {
    return $this->getUser()->setAttribute('regulation.filters', $filters, 'admin_module');
  }

  protected function getPager()
  {
    $pager = $this->configuration->getPager('regulation');
    $pager->setCriteria($this->buildCriteria());
    $pager->setPage($this->getPage());
    $pager->setPeerMethod($this->configuration->getPeerMethod());
    $pager->setPeerCountMethod($this->configuration->getPeerCountMethod());
    $pager->init();

    return $pager;
  }

  protected function setPage($page)
  {
    $this->getUser()->setAttribute('regulation.page', $page, 'admin_module');
  }

  protected function getPage()
  {
    return $this->getUser()->getAttribute('regulation.page', 1, 'admin_module');
  }

  protected function buildCriteria()
  {
    if (null === $this->filters)
    {
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }

    $criteria = $this->filters->buildCriteria($this->getFilters());

    $this->addSortCriteria($criteria);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_criteria'), $criteria);
    $criteria = $event->getReturnValue();

    return $criteria;
  }

  protected function addSortCriteria($criteria)
  {
    if (array(null, null) == ($sort = $this->getSort()))
    {
      return;
    }

    $column = RegulationPeer::translateFieldName($sort[0], BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_COLNAME);
    if ('asc' == $sort[1])
    {
      $criteria->addAscendingOrderByColumn($column);
    }
    else
    {
      $criteria->addDescendingOrderByColumn($column);
    }
  }

  protected function getSort()
  {
    if (null !== $sort = $this->getUser()->getAttribute('regulation.sort', null, 'admin_module'))
    {
      return $sort;
    }

    $this->setSort($this->configuration->getDefaultSort());

    return $this->getUser()->getAttribute('regulation.sort', null, 'admin_module');
  }

  protected function setSort(array $sort)
  {
    if (null !== $sort[0] && null === $sort[1])
    {
      $sort[1] = 'asc';
    }

    $this->getUser()->setAttribute('regulation.sort', $sort, 'admin_module');
  }

  protected function isValidSortColumn($column)
  {
    return in_array($column, BasePeer::getFieldnames('regulation', BasePeer::TYPE_FIELDNAME));
  }
}
