<?php if ($pager->getNbResults()): ?>
    <div class="">
        <table cellspacing="0" class="table table-admin">
            <thead>
                <tr>
                    <?php include_partial('unpaid/list_th_tabular', array('sort' => $sort)) ?>
                </tr>
            </thead>
            <tbody>
                <?php $currencyCode = 'CZK' ?>
                <?php foreach ($pager->getResults() as $i => $unpaid): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
                    <tr class="sf_admin_row <?php echo $odd ?>">
                        <?php include_partial('unpaid/list_td_tabular', array('unpaid' => $unpaid)) ?>
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
                        <?php echo my_format_currency($sums['unpaid'], $currencyCode) ?>
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
