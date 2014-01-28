<?php

class ToPayReportForm extends ParentReportForm
{

    
    public function getUsedFields()
    {
        return array('date_to', 'creditor_id', 'contract_type_id', 'contract_id');
    }

    public function configure()
    {
        parent::configure();

        $this->getWidget('creditor_id')->setOption('add_empty', false);
        $this->excludeOwnerFromCreditors();
    }
}
