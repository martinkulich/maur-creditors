<?php

class CreditorsReportForm extends ParentReportForm
{  
    public function getUsedFields()
    {
        return array('year', 'creditor_id', 'currency_code');
    }

    public function configure()
    {
        parent::configure();

        $this->excludeOwnerFromCreditors();
    }
}
