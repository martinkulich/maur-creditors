<?php

class MonthlyReportForm extends ParentReportForm
{

    public function getUsedFields()
    {
        return array('month', 'year');
    }
}
