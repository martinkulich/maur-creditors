<table class="table table-admin">
    <thead>
        <th>
            <?php echo link_to(__('Date'), '@report_sort?sort=date&report_type='.$reportType)?>
        </th>
        <th>
            <?php echo link_to(__('User'), '@report_sort?sort=note&report_type='.$reportType)?>
        </th>
        <th>
            <?php echo link_to(__('Sport'), '@report_sort?sort=sport&report_type='.$reportType)?>
        </th>
        <th>
            <?php echo link_to(__('Price category'), '@report_sort?sort=price_category&report_type='.$reportType)?>
        </th>
        <th>
            <?php echo link_to(__('Paid'), '@report_sort?sort=paid&report_type='.$reportType)?>
        </th>
        <th class="span2 text-align-right">
            <?php echo link_to(__('Amount'), '@report_sort?sort=amount&report_type='.$reportType)?>
        </th>
        <th class="span2 text-align-right">
            <?php echo link_to(__('Sale'), '@report_sort?sort=sale&report_type='.$reportType)?>
        </th>
        <th class="span2 text-align-right">
            <?php echo link_to(__('Total'), '@report_sort?sort=total_amount&report_type='.$reportType)?>
        </th>
    </thead>
    <tbody>
        <?php foreach($data['rows'] as $row){?>
        <tr>
            <td>
                <?php echo link_to(format_date($row['date'], 'D'), '@reservation_detail?id='.$row['id'], array('class'=>'modal_link')); ?>
            </td>
            <td>
                <?php echo $row['user_fullname']?>
            </td>
            <td>
                <?php echo $row['sport']?>
            </td>
            <td>
                <?php echo $row['price_category']?>
            </td>
            <td>
                <?php echo $row['paid'] ? image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/tick.png') : ''?>
            </td>
            <td class="text-align-right">
                <?php echo format_currency($row['amount'], 'CZK')?>
            </td>
            <td class="text-align-right">
                <?php echo format_currency($row['sale'], 'CZK')?>
            </td>
            <td class="text-align-right">
                <?php echo format_currency($row['total_amount'], 'CZK')?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5">
                <?php echo __('Total')?>
            </th>
            <th class="text-align-right">
                <?php echo format_currency($data['total']['amount'], 'CZK')?>
            </th>
            <th class="text-align-right">
                <?php echo format_currency($data['total']['sale'], 'CZK')?>
            </th>
            <th class="text-align-right">
                <?php echo format_currency($data['total']['total_amount'], 'CZK')?>
            </th>
        </tr>
    </tfoot>
</table>
