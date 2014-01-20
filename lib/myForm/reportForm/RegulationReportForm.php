<?php

class RegulationReportForm extends ParentReportForm
{
    public function getUsedFields()
    {
        return array('debtor_id', 'creditor_id', 'contract_id', 'years');
    }
}
