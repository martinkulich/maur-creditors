<?php use_helper('I18N', 'Date') ?>
<div class="modal_content">
    <div class="modal-header" >
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo $outgoinPayment ?></h3>
    </div>

    <div class="modal-body">
        <?php include_component('default', 'flashes') ?>
        <?php echo $outgoinPayment->getNote() ?>

    </div>
</div>