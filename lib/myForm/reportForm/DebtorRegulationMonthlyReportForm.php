<?php

class DebtorRegulationMonthlyReportForm extends ParentReportForm
{
    public function getUsedFields()
    {
        return array('year', 'month', 'debtor_id');
    }

    public function configure()
    {
        parent::configure();
        $this->excludeOwnerFromDebtors();
    }
}
