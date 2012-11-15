<?php use_helper('Number')?>
<table class="table table-admin table-bordered">
    <thead>
        <tr>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Creditor'), '@report_sort?sort=creditor_fullname&report_type='.$reportType)?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Balance'), '@report_sort?sort=balance&report_type='.$reportType)?>
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
                    <?php echo $isCurrency ? format_currency($row[$column], $row['currency_code']) : $row[$column] ?>
                </td>
            <?php }?>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <?php foreach($data['currency_codes'] as $currency){?>
            <tr>
                <th>
                    <?php echo __('Total').' '.__($currency)?>
                </th>
                <?php foreach($data['columns'] as $column=>$isCurrency){?>
                    <th class="text-align-right">
                        <?php echo $isCurrency ? format_currency($data['total'][$column][$currency], $currency) : $data['total'][$column][$currency] ?>
                    </th>
                <?php }?>
            </tr>
        <?php }?>
    </tfoot>
</table>
