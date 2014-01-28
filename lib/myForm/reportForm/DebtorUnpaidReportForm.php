<?php

class DebtorUnpaidReportForm extends ParentReportForm
{

    public function configure()
    {
        parent::configure();
        $this->getWidget('date_to')->setLabel('Unpaid to date');
        $this->excludeOwnerFromDebtors();
    }

    public function getUsedFields()
    {
        return array('date_to', 'debtor_id');
    }
}
