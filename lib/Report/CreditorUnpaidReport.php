<?php

class CreditorUnpaidReport extends UnpaidReport
{
    public function getReportCode()
    {
        return 'creditor_unpaid';
    }

    public function getConditions()
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
