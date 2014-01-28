<?php

class CreditorRegulationReportForm extends ParentReportForm
{
    public function getUsedFields()
    {
        return array('creditor_id', 'contract_id', 'years');
    }

    public function configure()
    {
        parent::configure();
        $this->excludeOwnerFromCreditors();

    }
}
