<?php

class ToPayReport extends CommitmentReport
{
    public function getReportCode()
    {
        return 'to_pay';
    }

    public function getConditions()
    {
        return $this->getCreditorReportConditions();
    }


    public function getColumns()
    {
        $columns = parent::getColumns();
        $columns = array_diff($columns, array('debtor_fullname'));

        return $columns;
    }

    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);

        if($column == 'exclude_from_report')
        {
            $formatedValue = link_to(ServiceContainer::getTranslateService()->__('exclude_from_report'), '@contract_excludeFromReport?report_type=to_pay&id='.$row['contract_id']);
        }

        return $formatedValue;
    }

    public function getDefaultOrderBy()
    {
        return 'creditor_fullname';
    }

}
