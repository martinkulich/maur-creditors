<?php use_helper('I18N', 'Date') ?>

<div id="sf_admin_container">

  <div id="sf_admin_header">
    <?php include_partial('contract/list_header', array('pager' => $pager)) ?>
  </div>


  <div id="sf_admin_content">
    <?php include_partial('contract/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    <ul class="sf_admin_list_actions">
      <?php include_partial('contract/list_batch_actions', array('helper' => $helper)) ?>
      <?php include_partial('contract/list_actions', array('helper' => $helper, 'pager'=>$pager, 'showResetFilter'=>$showResetFilter)) ?>
    </ul>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('contract/list_footer', array('pager' => $pager)) ?>
  </div>
</div>