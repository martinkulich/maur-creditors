<?php

class CreditorRegulationMonthlyReport extends RegulationMonthlyReport
{

    protected function getConditions()
    {
        $conditions = parent::getConditions();
        $conditions[] = $this->getOwnerAsDebtorCondition();
        $conditions[] = $this->getExcludeOwnerFromCreditorsCondition();

        return $conditions;
    }

    public function getColumns()
    {
        $columns = parent::getColumns();
        $columns = array_diff($columns, array('debtor_fullname'));

        return $columns;
    }
}
