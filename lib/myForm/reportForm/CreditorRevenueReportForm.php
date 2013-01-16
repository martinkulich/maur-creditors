<?php

class CreditorRevenueReportForm extends ParentReportForm
{  
    public function getUsedFields()
    {
        return array('date_to', 'creditor_id');
    }
}
