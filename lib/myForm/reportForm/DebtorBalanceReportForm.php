<?php

class DebtorBalanceReportForm extends ParentReportForm
{

    public function configure()
    {
        parent::configure();
        $this->getWidget('date_to')->setLabel('Balance to date');
        $this->excludeOwnerFromDebtors();
    }
    
    public function getUsedFields()
    {
        return array('date_to', 'debtor_id');
    }
}
