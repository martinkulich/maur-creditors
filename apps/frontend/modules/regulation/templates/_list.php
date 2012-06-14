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
      <tfoot>
          <tr class="sf_admin_row">
              <th>
              </th>
              <th>
              </th>
              <th>
              </th>
              <th>
                  <?php echo format_currency($sums['start_balance'], $currencyCode) ?>
              </th>
              <th>
              </th>
              <th>
                  <?php echo format_currency($sums['contract_balance'], $currencyCode) ?>
              </th>
              <th>
                      <?php echo format_currency($sums['regulation'], $currencyCode) ?>
              </th>
              <th>
                      <?php echo format_currency($sums['paid'], $currencyCode) ?>
              </th>
              <th>
                      <?php echo format_currency($sums['paid_for_current_year'], $currencyCode) ?>
              </th>
              <th>
                      <?php echo format_currency($sums['capitalized'], $currencyCode) ?>
              </th>
              <th>
                      <?php echo format_currency($sums['teoreticaly_to_pay_in_current_year'], $currencyCode) ?>
              </th>
              <th>
                  <?php echo format_currency($sums['unpaid'], $currencyCode) ?>
              </th>
              <th>
                  <?php echo format_currency($sums['end_balance'], $currencyCode) ?>
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
