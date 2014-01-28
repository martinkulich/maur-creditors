<?php

class DebtorRegulationReport extends RegulationReport
{

    public function getConditions()
    {
        return $this->getDebtorReportConditions();
    }


    public function getColumns()
    {
        $columns = parent::getColumns();
        $columns = array_diff($columns, array('creditor_fullname'));

        return $columns;
    }


}
