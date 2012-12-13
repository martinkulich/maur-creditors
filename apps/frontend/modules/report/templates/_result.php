<?php use_helper('Number')?>
<table class="table table-admin table-bordered">
    <thead>
        <tr>
            <?php foreach($report->getColumns() as $column){ ?>
                <th class="span3 <?php echo $report->getColumnHeaderClass($column)?>">
                    <?php echo link_to(__(sfInflector::humanize($column)), sprintf('@report_sort?sort=%s&report_type=%s',$column, $reportType))?>
                </th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php if($report->hasValidFilters()){ ?>
            <?php foreach($report->getData() as $row){?>
            <tr>
                <?php foreach($report->getColumns() as $column){?>
                    <td class="<?php echo $report->getColumnRowClass($column, $row)?>">
                        <?php echo $report->getFormatedValue($row, $column) ?>
                    </td>
                <?php }?>           
            </tr>
            <?php } ?>
            <?php if($report->hasTotals()){ ?>
                <?php foreach($report->getTotals() as $rowId=>$totalRow){ ?>
                    <tr>
                    <?php foreach($report->getColumns() as $column){ ?>
                        <th class="<?php echo $report->getColumnRowClass($column)?>"> 
                            <?php if($report->hasTotalColumn($column)){ ?>
                                <?php echo $report->getFormatedValue($totalRow, $column) ?>
                            <?php }?>
                        </th>
                    <?php } ?>
                    </tr>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>
