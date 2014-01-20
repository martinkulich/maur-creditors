<?php use_helper('I18N', 'Date') ?>
<div class="modal_content">
    <div class="modal-header" >
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo $subject->getFullname(). ' - '. __('New gift') ?></h3>
    </div>
    <?php include_partial('subject/add_gift_form', array('subject' => $subject, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>