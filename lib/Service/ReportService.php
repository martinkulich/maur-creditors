<?php

class ReportService
{

    public function getReport($reportType, array $filters = array())
    {
        $reportClass = sfInflector::camelize($reportType) . 'Report';
        if (!class_exists(($reportClass))) {
            throw new exception(sprintf('Undefined report class %s', $reportClass));
        }
        $report = new $reportClass($filters);

        return $report;
    }
    
        /**
     * @param string $reportType
     * @return ReportForm $reportForm
     */
    public function getForm($reportType)
    {
        $defaultReportForm = 'ReportForm';
        $formClass = sfInflector::camelize($reportType) . $defaultReportForm;
        if (!class_exists(($formClass))) {
            $formClass = $defaultReportForm;
        }
        $form = new $formClass;

        return $form;
    }
    
}
