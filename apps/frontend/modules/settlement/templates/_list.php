<?php if ($pager->getNbResults()): ?>
  <div class="">
    <table cellspacing="0" class="table table-admin">
      <thead>
        <tr>
          <?php include_partial('settlement/list_th_tabular', array('sort' => $sort)) ?>
          <th id="sf_admin_list_th_actions"></th>
        </tr>
      </thead>
      <tbody>
        <?php $ownerIdentificationNumber = sfConfig::get('app_owner_identification_number') ?>
        <?php foreach ($pager->getResults() as $i => $settlement): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
                <?php $currencyCode = $settlement->getContract()->getCurrencyCode()?>
          <tr class="sf_admin_row <?php echo $odd.' '; echo ($settlement->isSettlementType(SettlementPeer::END_OF_YEAR) ? ' text-light-blue ' : '')?> <?php if($settlement->getContract()->getCreditor()->getIdentificationNumber() == $ownerIdentificationNumber) { echo ' owner_as_creditor '; }else { echo ' owner_as_debtor '; }?> ">
            <?php include_partial('settlement/list_td_tabular', array('settlement' => $settlement)) ?>
            <?php include_partial('settlement/list_td_actions', array('settlement' => $settlement, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
        <tfoot>
        <?php foreach($sums as $currencyCode=>$sumValues){?>
            <tr class="sf_admin_row ">
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td class="text-align-right">
                        <?php echo my_format_currency($sumValues['interest'], $currencyCode) ?>
                </td>
                <td class="text-align-right">
                        <?php echo my_format_currency($sumValues['paid'], $currencyCode) ?>
                </td>

                <td class="text-align-right">
                        <?php echo my_format_currency($sumValues['capitalized'], $currencyCode) ?>
                </td>
                <td class="text-align-right">
                    <?php echo my_format_currency($sumValues['balance_increase'], $currencyCode) ?>
                </td>
                <td class="text-align-right">
                        <?php echo my_format_currency($sumValues['balance_reduction'], $currencyCode) ?>
                </td>
                <td class="text-align-right">
                    <?php echo my_format_currency($sumValues['unsettled'], $currencyCode) ?>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td class="no-print">
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
