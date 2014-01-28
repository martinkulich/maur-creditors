<?php

class DebtorConfirmationReportForm extends ParentReportForm
{

    public function getUsedFields()
    {
        return array('year', 'contract_type_id', 'debtor_id');

    }

    public function configure()
    {
        parent::configure();

        $this->getWidget('debtor_id')->setOption('add_empty', false);
        $this->excludeOwnerFromDebtors();
    }
}
