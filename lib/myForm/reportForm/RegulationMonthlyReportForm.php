<?php

class RegulationMonthlyReportForm extends ParentReportForm
{
    public function getUsedFields()
    {
        return array('year', 'month','debtor_id', 'creditor_id');
    }
}
