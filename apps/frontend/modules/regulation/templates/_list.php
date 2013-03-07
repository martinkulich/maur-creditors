<?php if ($pager->getNbResults()): ?>
    <div class="">
        <table cellspacing="0" class="table table-admin">
            <thead>
                <tr>
                    <?php include_partial('regulation/list_th_tabular', array('sort' => $sort)) ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pager->getResults() as $i => $regulation): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
                    <tr class="sf_admin_row <?php echo $odd ?>">
                        <?php include_partial('regulation/list_td_tabular', array('regulation' => $regulation)) ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <?php foreach($sums as $currencyCode=>$sumValues){?>
                    <tr class="sf_admin_row">
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td class="text-align-right">
                            <?php if (array_key_exists('start_balance', $sumValues)) { ?>
                                <?php echo my_format_currency($sumValues['start_balance'], $currencyCode) ?>
                            <?php } ?>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            <?php if (array_key_exists('unpaid_in_past', $sumValues)) { ?>
                                <?php echo my_format_currency($sumValues['unpaid_in_past'], $currencyCode) ?>
                            <?php } ?>
                        </td>
                        <td class="text-align-right">
                            <?php echo my_format_currency($sumValues['regulation'], $currencyCode) ?>
                        </td>
                        <td class="text-align-right">
                            <?php echo my_format_currency($sumValues['paid'], $currencyCode) ?>
                        </td>
                        <td class="text-align-right">
                            <?php echo my_format_currency($sumValues['paid_for_current_year'], $currencyCode) ?>
                        </td>
                        <td class="text-align-right">
                            <?php echo my_format_currency($sumValues['capitalized'], $currencyCode) ?>
                        </td>
                        <td class="text-align-right">
                            <?php if (array_key_exists('unpaid', $sumValues)) { ?>
                                <?php echo my_format_currency($sumValues['unpaid'], $currencyCode) ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (array_key_exists('end_balance', $sumValues)) { ?>
                                <?php echo my_format_currency($sumValues['end_balance'], $currencyCode) ?>
                            <?php } ?>
                        </td>
                    </tr>
                 <?php }?>
            </tfoot>
        </table>
    </div>
<?php endif; ?>
<script type="text/javascript">
    /* <![CDATA[ */
    function checkAll()
    {
        var boxes = document.getElementsByTagName('input'); for(var index = 0; index < boxes.length; index++) { box = boxes[index]; if (box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox') box.checked = document.getElementById('sf_admin_list_batch_checkbox').checked } return true;
    }
    /* ]]> */
</script>
