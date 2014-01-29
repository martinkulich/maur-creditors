<?php

class DebtorRegulationReport extends RegulationReport
{

    public function getReportCode()
    {
        return 'debtor_regulation';
    }
    public function getConditions()
    {
        $conditions = parent::getConditions();
        $debtorConditions =  $this->getDebtorReportConditions();
        return array_merge($conditions, $debtorConditions);
    }


    public function getColumns()
    {
        $columns = parent::getColumns();
        $columns = array_diff($columns, array('creditor_fullname'));

        return $columns;
    }


}
