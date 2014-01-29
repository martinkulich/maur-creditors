<?php

class CashFlowReportForm extends ParentReportForm
{

    public function getUsedFields()
    {
        return array('month', 'year');
    }
}
