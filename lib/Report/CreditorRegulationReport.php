<?php

class CreditorRegulationReport extends RegulationReport
{
    public function getReportCode()
    {
        return 'creditor_regulation';
    }

    public function getConditions()
    {
        $conditions = parent::getConditions();
        $creditorConditions =  $this->getCreditorReportConditions();
        return array_merge($conditions, $creditorConditions);
    }


    public function getColumns()
    {
        $columns = parent::getColumns();
        $columns = array_diff($columns, array('debtor_fullname'));

        return $columns;
    }


}
