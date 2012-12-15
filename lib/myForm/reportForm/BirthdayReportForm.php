<?php

class BirthdayReportForm extends ReportForm
{
    
    public function getUsedFields()
    {
        return array('creditor_id');
    }
}
