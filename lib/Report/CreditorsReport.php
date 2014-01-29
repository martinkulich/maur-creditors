<?php

class CreditorsReport extends SubjectsReport
{
    public function getReportCode()
    {
        return 'creditors';
    }

    public function getConditions()
    {
        return $this->getCreditorReportConditions();
    }
}
