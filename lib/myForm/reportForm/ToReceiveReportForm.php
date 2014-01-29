<?php

class ToReceiveReportForm extends ParentReportForm
{

    
    public function getUsedFields()
    {
        return array('date_to', 'debtor_id', 'contract_type_id');
    }

    public function configure()
    {
        parent::configure();
        $this->excludeOwnerFromDebtors();
    }
}
