<?php

class CreditorRevenueReportForm extends ParentReportForm
{  
    public function getUsedFields()
    {
        return array('date_to', 'debtor_id', 'creditor_id');
    }

    public function configure()
    {
        parent::configure();

        $this->getWidget('debtor_id')->setOption('add_empty', false);
    }
}
