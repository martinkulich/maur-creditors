<?php

class UnpaidReportForm extends ReportForm
{

    public function configure()
    {
        parent::configure();
        $this->getWidget('date_to')->setLabel('Unpaid to date');
    }
    
    public function getUsedFields()
    {
        return array('date_to', 'creditor_id');
    }
}
