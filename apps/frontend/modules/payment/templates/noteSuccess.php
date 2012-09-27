<?php use_helper('I18N', 'Date') ?>
<div class="modal_content">
    <div class="modal-header" >
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo $payment->getContract()->getCreditor(). ' - '.$payment->getContract() ?></h3>
    </div>

    <div class="modal-body">
        <?php include_component('default', 'flashes') ?>
        <?php echo $payment->getNote() ?>

    </div>
</div>