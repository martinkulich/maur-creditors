<?php

class DebtorRegulationReportForm extends ParentReportForm
{
    public function getUsedFields()
    {
        return array('debtor_id', 'contract_id', 'years');
    }

    public function configure()
    {
        parent::configure();
        $this->excludeOwnerFromDebtors();

    }
}
