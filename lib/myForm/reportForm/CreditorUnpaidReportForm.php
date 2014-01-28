<?php

class CreditorUnpaidReportForm extends ParentReportForm
{

    public function configure()
    {
        parent::configure();
        $this->getWidget('date_to')->setLabel('Unpaid to date');
        $this->excludeOwnerFromCreditors();
    }

    public function getUsedFields()
    {
        return array('date_to', 'creditor_id');
    }
}
