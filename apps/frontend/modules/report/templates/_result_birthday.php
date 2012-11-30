<?php use_helper('Number')?>
<table class="table table-admin table-bordered">
    <thead>
        <tr>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Creditor'), '@report_sort?sort=creditor_fullname&report_type='.$reportType)?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Address'), '@report_sort?sort=creditor_address&report_type='.$reportType)?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Next birthday'), '@report_sort?sort=creditor_next_birthday&report_type='.$reportType)?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Age on next birthday'), '@report_sort?sort=creditor_age&report_type='.$reportType)?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data['rows'] as $rowKey=>$row){?>
        <tr>
            <td>
                <?php echo $row['creditor_fullname']?>
            </td>
            <?php foreach($data['columns'] as $column=>$isCurrency){?>
                <td class="text-align-right">
                    <?php echo $column == 'creditor_next_birthday' ? format_date($row[$column], 'D') : $row[$column] ?>
                </td>
            <?php }?>
        </tr>
        <?php } ?>
    </tbody>
</table>
