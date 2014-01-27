<?php

class CreditorsReportForm extends ParentReportForm
{  
    public function getUsedFields()
    {
        return array('year', 'debtor_id', 'creditor_id', 'currency_code');
    }

    public function configure()
    {
        parent::configure();

        $this->getWidget('debtor_id')->setOption('add_empty', false);
    }
}
