<?php

class BalanceReportForm extends ParentReportForm
{

    public function configure()
    {
        parent::configure();
        $this->getWidget('date_to')->setLabel('Balance to date');
    }
    
    public function getUsedFields()
    {
        return array('date_to', 'creditor_id');
    }
}
