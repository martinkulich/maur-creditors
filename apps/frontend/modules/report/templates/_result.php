<?php use_helper('Number') ?>
<table class="table table-admin table-bordered table-fixed-header">
    <thead>
    <tr>
        <?php foreach ($report->getColumns() as $column) { ?>
        <th class="span3 <?php echo $report->getColumnHeaderClass($column)?>">
            <?php echo link_to(__(sfInflector::humanize($report->getColumnHeader($column))), sprintf('@report_sort?sort=%s&report_type=%s', $column, $reportType))?>
        </th>
        <?php } ?>
    </tr>
    </thead>
    <tfoot>
    <?php if ($report->hasTotals()) { ?>
        <?php foreach ($report->getTotals() as $rowId => $totalRow) { ?>
        <tr>
            <?php foreach ($report->getColumns() as $column) { ?>
            <td class="<?php echo $report->getColumnRowClass($column)?>">
                <?php if ($report->hasTotalColumn($column)) { ?>
                <?php echo $report->getFormatedValue($totalRow, $column) ?>
                <?php }?>
            </td>
            <?php } ?>
        </tr>
            <?php } ?>
        <?php } ?>
    </tfoot>

    <tbody>
    <?php if ($report->hasValidFilters()) { ?>
        <?php foreach ($report->getData() as $row) { ?>
        <tr>
            <?php foreach ($report->getColumns() as $column) { ?>
            <td class="<?php echo $report->getColumnRowClass($column, $row)?>">
                <?php echo $report->getFormatedRowValue($row, $column) ?>
            </td>
            <?php }?>
        </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>
<?php if(count($report->getData()) < 5){?>
<?php }?>