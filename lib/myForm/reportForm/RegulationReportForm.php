<?php

class RegulationReportForm extends ParentReportForm
{
    public function configure()
    {
        parent::configure();
        $this->getWidget('year')->setOption('add_empty', true);
        $this->getValidator('year')->setOption('required', false);
    }
    public function getUsedFields()
    {
        return array('creditor_id', 'contract_id', 'year');
    }
}
