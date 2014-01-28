<?php

class DebtorRegulationReport extends RegulationReport
{

    public function getConditions()
    {
        $conditions = parent::getConditions();
        $conditions[] = $this->getOwnerAsCreditorCondition();
        $conditions[] = $this->getExcludeOwnerFromDebtorsCondition();
        return $conditions;
    }


    public function getColumns()
    {
        $columns = parent::getColumns();
        $columns = array_diff($columns, array('creditor_fullname'));

        return $columns;
    }


}
