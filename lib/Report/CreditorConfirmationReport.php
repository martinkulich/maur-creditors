<?php

class CreditorConfirmationReport extends ConfirmationReport
{
    public function getReportCode()
    {
        return 'creditor_confirmation';
    }

    protected function getConditions()
    {
        return $this->getCreditorReportConditions();
    }

    public function getColumns()
    {
        $columns = parent::getColumns();
        $columns = array_diff($columns, array('debtor_fullname', 'debtor_address'));

        return $columns;
    }

    public function getRequiredFilters()
    {
        $requiredFilters = parent::getRequiredFilters();
        $requiredFilters[] = 'creditor_id';

        return $requiredFilters;
    }
}
