<?php

class CreditorRegulationMonthlyReportForm extends ParentReportForm
{
    public function getUsedFields()
    {
        return array('year', 'month', 'creditor_id');
    }

    public function configure()
    {
        parent::configure();
        $this->excludeOwnerFromCreditors();
    }
}
