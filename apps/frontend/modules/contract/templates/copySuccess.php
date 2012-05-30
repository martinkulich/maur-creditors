<?php use_helper('I18N', 'Date') ?>
<div class="modal_content">
    <div class="modal-header" >
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('Copy '.$this->getModuleName()) ?></h3>
    </div>
    <?php include_partial('contract/copy_form', array('contract' => $contract, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>