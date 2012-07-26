<?php if ($pager->getNbResults()): ?>
  <div class="">
    <table cellspacing="0" class="table table-admin">
      <thead>
        <tr>
          <?php include_partial('payment/list_th_tabular', array('sort' => $sort)) ?>
          <th id="sf_admin_list_th_actions"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pager->getResults() as $i => $payment): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?>">
            <?php include_partial('payment/list_td_tabular', array('payment' => $payment)) ?>
            <?php include_partial('payment/list_td_actions', array('payment' => $payment, 'helper' => $helper)) ?>
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
              <th class="text-align-right">
                  <?php echo my_format_currency($sums['amount'], $payment->getContract()->getCurrencyCode()) ?>
              </th>
              <th>
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
