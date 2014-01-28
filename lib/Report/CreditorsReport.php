<?php

class CreditorsReport extends SubjectsReport
{
    public function getConditions()
    {
        return $this->getCreditorReportConditions();
    }
}
