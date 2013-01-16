<?php

class BirthdayReportForm extends ParentReportForm
{
    
    public function getUsedFields()
    {
        return array('creditor_id');
    }
}
