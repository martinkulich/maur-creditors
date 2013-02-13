<?php

class RegulationReportForm extends ParentReportForm
{
    public function getUsedFields()
    {
        return array('creditor_id', 'contract_id', 'years');
    }
}
