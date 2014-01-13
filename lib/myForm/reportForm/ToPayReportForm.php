<?php

class ToPayReportForm extends ParentReportForm
{

    
    public function getUsedFields()
    {
        return array('date_to', 'creditor_id', 'contract_type_id', 'contract_id');
    }
}
