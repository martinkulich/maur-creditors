<?php

class CreditorConfirmationReportForm extends ParentReportForm
{

    public function getUsedFields()
    {
        return array('year', 'contract_type_id', 'creditor_id');

    }

    public function configure()
    {
        parent::configure();

        $this->getWidget('creditor_id')->setOption('add_empty', false);
    }
}
