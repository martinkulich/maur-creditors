<?php

/**
 * report actions.
 *
 * @package    rezervuj
 * @subpackage report
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reportActions extends sfActions
{

    public function preExecute()
    {
        $request = $this->getRequest();
        $this->reportType = $request->getParameter('report_type');

        $this->reportService = ServiceContainer::getReportService();
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        $this->data = $this->reportService->getData($this->reportType, $this->getFilters());

        $this->hasFilter = count($this->getFilters())>0;
    }

    public function executeFilters(sfWebRequest $request)
    {
        $this->form = $this->getForm($request);
        $this->form->bind($this->getFilters());
    }

    public function executeSort(sfWebRequest $request)
    {
        $sort = $request->getParameter('sort');

        $filters = $this->getFilters();
        $filters['sort'] = $sort;
        $sortType = 'asc';
        if(isset($filters['sort']) && $filters['sort'] == $sort && isset($filters['sort_type']) && $filters['sort_type'] == 'asc')
        {
            $sortType = 'desc';
        }
        $filters['sort_type'] = $sortType;

        $this->setFilters($filters);

        return $this->redirect('@report?report_type='.$this->reportType);
    }
    public function executeFilter(sfWebRequest $request)
    {
        $this->form = $this->getForm($request);

        $this->form->bind($request->getParameter($this->form->getName()));

        if ($this->form->isValid()) {
            $this->setFilters($this->form->getValues());

            return $this->redirect('@report?report_type='.$this->reportType, 205);
        } else {
            ServiceContainer::getMessageService()->addFromErrors($this->form, true);
        }

        $this->setTemplate('filters');
    }

      public function executeReset(sfWebRequest $request)
  {
      $this->setFilters(array());

      return $this->redirect('@report?report_type='.$this->reportType);
  }

    protected function getForm(sfWebRequest $request)
    {
        return $this->reportService->getForm($this->reportType);
    }

    protected function getFilters()
    {
        return $this->getUser()->getAttribute('report.filters', array(), 'report');
    }

    protected function setFilters(array $filters)
    {
        return $this->getUser()->setAttribute('report.filters', $filters, 'report');
    }
}
