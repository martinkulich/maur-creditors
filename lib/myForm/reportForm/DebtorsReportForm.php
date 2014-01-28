<?php

class DebtorsReportForm extends ParentReportForm
{  
    public function getUsedFields()
    {
        return array('year', 'debtor_id', 'currency_code');
    }

    public function configure()
    {
        parent::configure();

        $this->excludeOwnerFromDebtors();
    }
}
