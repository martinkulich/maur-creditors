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

        $credential = str_replace("_", "-", sprintf('report-%s', $this->reportType));
        if (!$this->getUser()->hasCredential($credential)) {
            return $this->forward('security', 'secure');
        }
       
        $this->reportService = ServiceContainer::getReportService();
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        $filters = $this->getFilters();
        $this->report = $this->reportService->getReport($this->reportType, $filters);
        $this->hasFilter = count($filters) > 0;
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
        if (isset($filters['sort']) && $filters['sort'] == $sort && isset($filters['sort_type']) && $filters['sort_type'] == 'asc') {
            $sortType = 'desc';
        }
        $filters['sort_type'] = $sortType;

        $this->setFilters($filters);

        return $this->redirect('@report?report_type=' . $this->reportType);
    }

    public function executeFilter(sfWebRequest $request)
    {
        $this->form = $this->getForm($request);

        $this->form->bind($request->getParameter($this->form->getName()));

        if ($this->form->isValid()) {
            $this->setFilters(array_merge($this->getFilters(), $this->form->getValues()));

            return $this->redirect('@report?report_type=' . $this->reportType, 205);
        } else {
            ServiceContainer::getMessageService()->addFromErrors($this->form, false);
        }

        $this->setTemplate('filters');
    }
    
    public function executeAddFilter(sfWebRequest $request)
    {
         if($filter = $request->getParameter('filter'))
        {
            $this->setFilters(array_merge($this->getFilters(), $filter));
        }
        return $this->redirect('@report?report_type=' . $this->reportType);
    }
    
    

    public function executeReset(sfWebRequest $request)
    {
        $this->setFilters(array());

        return $this->redirect('@report?report_type=' . $this->reportType);
    }

    protected function getForm(sfWebRequest $request)
    {
        return $this->reportService->getForm($this->reportType, $this->getFilters());
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
