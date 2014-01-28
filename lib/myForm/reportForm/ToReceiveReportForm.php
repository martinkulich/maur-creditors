<?php

class ToReceiveReportForm extends ParentReportForm
{

    
    public function getUsedFields()
    {
        return array('date_to', 'debtor_id', 'contract_type_id', 'contract_id');
    }

    public function configure()
    {
        parent::configure();

        $this->getWidget('debtor_id')->setOption('add_empty', false);
        $debtorCriteria = $this->getWidget('debtor_id')->getOption('criteria');

        $this->getWidget('debtor_id')->setOption('criteria', SubjectPeer::getExcludeOwnerCriteria($debtorCriteria));
    }
}
