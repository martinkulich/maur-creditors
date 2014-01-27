<?php

class UnpaidReportForm extends ParentReportForm
{

    public function configure()
    {
        parent::configure();
        $this->getWidget('date_to')->setLabel('Unpaid to date');
        $this->getWidget('debtor_id')->setOption('add_empty', false);
    }

    public function getUsedFields()
    {
        return array('date_to', 'debtor_id', 'creditor_id');
    }
}
