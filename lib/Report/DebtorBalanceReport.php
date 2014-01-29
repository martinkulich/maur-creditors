<?php

class DebtorBalanceReport extends BalanceReport
{

    public function getReportCode()
    {
        return 'debtor_balance';
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
