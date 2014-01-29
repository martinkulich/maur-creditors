<?php

class CreditorBalanceReport extends BalanceReport
{

    public function getReportCode()
    {
        return 'creditor_balance';

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
