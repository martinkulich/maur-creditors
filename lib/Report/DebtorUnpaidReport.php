<?php

class DebtorUnpaidReport extends UnpaidReport
{
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
