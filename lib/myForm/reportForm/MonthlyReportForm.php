<?php

class MonthlyReportForm extends ReportForm
{

    public function getUsedFields()
    {
        return array('month', 'year');
    }
}
