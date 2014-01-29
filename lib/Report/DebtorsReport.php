<?php

class DebtorsReport extends SubjectsReport
{
    public function getReportCode()
    {
        return 'debtors';
    }

    public function getConditions()
    {
        return $this->getDebtorReportConditions();
    }
}
