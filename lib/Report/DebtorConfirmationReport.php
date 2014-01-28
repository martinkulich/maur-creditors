<?php

class DebtorConfirmationReport extends ConfirmationReport
{

    protected function getConditions()
    {
        return $this->getDebtorReportConditions();
    }

    public function getColumns()
    {
        $columns = parent::getColumns();
        $columns = array_diff($columns, array('creditor_fullname', 'creditor_address'));

        return $columns;
    }

    public function getRequiredFilters()
    {
        $requiredFilters = parent::getRequiredFilters();
        $requiredFilters[] = 'debtor_id';

        return $requiredFilters;
    }
}
