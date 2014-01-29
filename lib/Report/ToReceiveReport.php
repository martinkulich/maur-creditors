<?php

class ToReceiveReport extends CommitmentReport
{
    public function getReportCode()
    {
        return 'to_receive';
    }
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

    public function getFormatedRowValue($row, $column)
    {
        $formatedValue = parent::getFormatedRowValue($row, $column);

        if($column == 'exclude_from_report')
        {
            $formatedValue = link_to(ServiceContainer::getTranslateService()->__('exclude_from_report'), '@contract_excludeFromReport?report_type=to_receive&id='.$row['contract_id']);
        }

        return $formatedValue;
    }

    public function getDefaultOrderBy()
    {
        return 'debtor_fullname';
    }
}
