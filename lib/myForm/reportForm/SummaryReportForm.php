<?php

class SummaryReportForm extends ParentReportForm
{

    public function configure()
    {
        parent::configure();
        $this->getWidget('date_to')->setLabel('Summary to date');
    }
    
    public function getUsedFields()
    {
        return array('date_to');
    }
}
