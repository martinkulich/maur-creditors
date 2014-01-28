<?php

class DebtorConfirmationReportForm extends ParentReportForm
{

    public function getUsedFields()
    {
        return array('year', 'contract_type_id', 'debtor_id');

    }

    public function configure()
    {
        parent::configure();

        $this->getWidget('debtor_id')->setOption('add_empty', false);
        if(!($debtorCriteria = $this->getWidget('debtor_id')->getOption('criteria')))
        {
            $debtorCriteria = new Criteria();
        }

        $debtorCriteria->add(SubjectPeer::IDENTIFICATION_NUMBER, sfConfig::get('app_owner_identification_number'), Criteria::NOT_EQUAL);
        $this->getWidget('debtor_id')->setOption('criteria', $debtorCriteria);
    }
}
