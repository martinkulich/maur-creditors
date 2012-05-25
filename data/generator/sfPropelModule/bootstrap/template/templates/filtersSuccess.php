[?php use_helper('I18N', 'Date') ?]
<div class="modal_content">
    <div class="modal-header" id="reservation_detail_header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>[?php echo __('Filter '.$this->getModuleName()) ?]</h3>
    </div>

    [?php include_partial('<?php echo $this->getModuleName() ?>/filter_form', array('form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?]
</div>
