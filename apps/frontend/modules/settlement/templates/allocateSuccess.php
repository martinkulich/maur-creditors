<?php use_helper('I18N', 'Date') ?>
<div class="modal_content">
    <div class="modal-header" >
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo $settlement->getContract()->getCreditor(). ' - '.$settlement->getContract(). ' - '. __('New allocation') ?></h3>
    </div>
    <?php include_partial('settlement/allocate_form', array('settlement' => $settlement, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>