[?php use_helper('I18N', 'Date') ?]
<div class="modal_content">
    <div class="modal-header" id="reservation_detail_header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>[?php echo __('New '.$this->getModuleName()) ?]</h3>
    </div>

    [?php include_partial('<?php echo $this->getModuleName() ?>/form', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?]
</div>
