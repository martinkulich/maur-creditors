<?php

class ToPayReportForm extends ReportForm
{

    
    public function getUsedFields()
    {
        return array('date_to', 'creditor_id', 'contract_id');
    }
}
