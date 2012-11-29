<?php use_helper('Number')?>
<table class="table table-admin table-bordered">
    <thead>
        <tr>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Creditor'), '@report_sort?sort=creditor_fullname&report_type='.$reportType)?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Unpaid'), '@report_sort?sort=creditor_unpaid&report_type='.$reportType, array('title'=>'Pokud je datum nastaveno na konec roku, jsou zde zahrnuty i úroky dopočítané do konce roku'))?>
            </th>
            <th class="span3 text-align-center">
                <?php echo link_to(__('Unpaid regular'), '@report_sort?sort=creditor_unpaid_regular&report_type='.$reportType, array('title'=>'Pokud je datum nastaveno na konec roku, nejsou  zde zahrnuty úroky dopočítané do konce roku, ale pouze úroky z řádných vypořádání'))?>
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
