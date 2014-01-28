<?php

class DebtorCostReportForm extends ParentReportForm
{  
    public function getUsedFields()
    {
        return array('date_to', 'debtor_id');
    }

    public function configure()
    {
        parent::configure();
        $this->excludeOwnerFromDebtors();
    }
}
