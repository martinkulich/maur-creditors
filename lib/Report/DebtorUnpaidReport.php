<?php

class DebtorUnpaidReport extends UnpaidReport
{
    public function getReportCode()
    {
        return 'debtor_unpaid';
    }

    public function getConditions()
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
