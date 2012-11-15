[?php use_helper('I18N', 'Date') ?]

<div id="sf_admin_container">
  <div id="sf_admin_content">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'sums'=>$sums)) ?]
  </div>
</div>