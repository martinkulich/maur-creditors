<?php

class DebtorsReport extends SubjectsReport
{
    public function getConditions()
    {
        return $this->getDebtorReportConditions();
    }
}
