<?php

class RegulationMonthlyReportForm extends ParentReportForm
{
    public function getUsedFields()
    {
        return array('year', 'month', 'creditor_id', 'currency_code');
    }
}
