<?php

class DebtorRegulationMonthlyReport extends RegulationMonthlyReport
{

    public function getReportCode()
    {
        return 'debtor_regulation_monthly';
    }
    protected function getConditions()
    {
        return $this->getDebtorReportConditions();
    }

    public function getColumns()
    {
        $columns = parent::getColumns();
        $columns = array_diff($columns, array('creditor_fullname'));

        return $columns;
    }
}
