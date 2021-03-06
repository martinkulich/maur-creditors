<?php if ($pager->getNbResults()): ?>
  <div class="">
    <table cellspacing="0" class="table table-admin">
      <thead>
        <tr>
          <?php include_partial('contract/list_th_tabular', array('sort' => $sort)) ?>
          <th id="sf_admin_list_th_actions"></th>
        </tr>
      </thead>
      <tbody>
      <?php $ownerIdentificationNumber = sfConfig::get('app_owner_identification_number') ?>
        <?php foreach ($pager->getResults() as $i => $contract): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?> <?php if($contract->getCreditor()->getIdentificationNumber() == $ownerIdentificationNumber) { echo ' owner_as_creditor '; }else { echo ' owner_as_debtor '; }?>">
            <?php include_partial('contract/list_td_tabular', array('contract' => $contract, 'currency'=>$currency)) ?>
            <?php include_partial('contract/list_td_actions', array('contract' => $contract, 'helper' => $helper)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
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
