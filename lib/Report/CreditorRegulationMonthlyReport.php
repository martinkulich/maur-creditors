<?php

class CreditorRegulationMonthlyReport extends RegulationMonthlyReport
{

    public function getReportCode()
    {
        return 'creditor_regulation_monthly';
    }

    protected function getConditions()
    {
        return $this->getCreditorReportConditions();
    }

    public function getColumns()
    {
        $columns = parent::getColumns();
        $columns = array_diff($columns, array('debtor_fullname'));

        return $columns;
    }
}
