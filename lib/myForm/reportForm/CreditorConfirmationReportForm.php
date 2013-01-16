<?php

class CreditorConfirmationReportForm extends ParentReportForm
{

    public function getUsedFields()
    {
        return array('year', 'creditor_id');
    }
}
