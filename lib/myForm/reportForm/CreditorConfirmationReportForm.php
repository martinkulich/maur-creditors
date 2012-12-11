<?php

class CreditorConfirmationReportForm extends ReportForm
{

    public function getUsedFields()
    {
        return array('year', 'creditor_id');
    }
}
