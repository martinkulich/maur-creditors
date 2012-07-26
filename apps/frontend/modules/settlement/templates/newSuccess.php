<?php use_helper('I18N', 'Date') ?>
<div class="modal_content">
    <div class="modal-header" id="reservation_detail_header">
        <a class="close" data-dismiss="modal">×</a>
        <?php if($settlement->getContractId()){ ?>
            <h3><?php echo $settlement->getContract()->getCreditor(). ' - '.$settlement->getContract() ?></h3>
        <?php }else{?>
            <h3><?php echo __('New '.$this->getModuleName()) ?></h3>
        <?php }?>
    </div>

    <?php include_partial('settlement/form', array('settlement' => $settlement, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
