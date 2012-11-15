[?php use_helper('I18N', 'Date') ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/printList', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'sums'=>$sums)) ?]
