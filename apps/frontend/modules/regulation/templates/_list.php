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
                    <?php $currencyCode = 'CZK' ?>
                    <tr class="sf_admin_row <?php echo $odd ?>">
                        <?php include_partial('regulation/list_td_tabular', array('regulation' => $regulation)) ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="no-wrap-line">
                <tr class="sf_admin_row">
                    <th>
                    </th>
                    <th>
                    </th>
                    <th>
                    </th>
                    <th>
                    </th>
                    <th>
                    </th>
                    <th>
                        <?php echo my_format_currency($sums['start_balance'], $currencyCode) ?>
                    </th>

                    <th>
                    </th>
                    <th class="text-align-right">
                        <?php echo my_format_currency($sums['regulation'], $currencyCode) ?>
                    </th>
                    <th class="text-align-right">
                        <?php echo my_format_currency($sums['paid'], $currencyCode) ?>
                    </th>
                    <th class="text-align-right">
                        <?php echo my_format_currency($sums['paid_for_current_year'], $currencyCode) ?>
                    </th>
                    <th class="text-align-right">
                        <?php echo my_format_currency($sums['capitalized'], $currencyCode) ?>
                    </th>
                    <th class="text-align-right">
                        <?php if (array_key_exists('unpaid', $sums)) { ?>
                            <?php echo my_format_currency($sums['unpaid'], $currencyCode) ?>
                        <?php } ?>
                    </th>
                    <th>
                    </th>
                </tr>
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
