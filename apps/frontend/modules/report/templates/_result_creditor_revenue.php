<?php use_helper('Number')?>
<table class="table table-admin table-bordered">
    <thead>
        <tr>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Creditor'), '@report_sort?sort=creditor_fullname&report_type='.$reportType)?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Creditor received payments'), '@report_sort?sort=creditor_unpaid&report_type='.$reportType, array('title'=>'Součet všech přijatých plateb  věřitele'))?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Creditor current balance'), '@report_sort?sort=creditor_current_balance&report_type='.$reportType, array('title'=>'Součet jistin všech aktivních smluv věřitele'))?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Creditor balance change'), '@report_sort?sort=creditor_balance_change&report_type='.$reportType, array('title'=>'Kapitalizace mínus ponížení jistiny'))?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Capitalized'), '@report_sort?sort=creditor_capitalized_&report_type='.$reportType, array('title'=>'Součet řádných úroků všech smluv '))?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Balance reduction'), '@report_sort?sort=creditor_balance_reduction&report_type='.$reportType, array('title'=>'Součet řádných úroků všech smluv '))?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Creditor interest regular'), '@report_sort?sort=creditor_interest_regular&report_type='.$reportType, array('title'=>'Součet řádných úroků všech smluv '))?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Creditor paid'), '@report_sort?sort=creditor_paid&report_type='.$reportType, array('title'=>'Součet všech výplat'))?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Creditor unpaid regular cumulative'), '@report_sort?sort=creditor_unpaid_regular&report_type='.$reportType)?>
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
                    <?php echo $isCurrency ? my_format_currency($row[$column], $row['currency_code']) : $row[$column] ?>
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
                        <?php echo $isCurrency ? my_format_currency($data['total'][$column][$currency], $currency) : $data['total'][$column][$currency] ?>
                    </th>
                <?php }?>
            </tr>
        <?php }?>
    </tfoot>
</table>
