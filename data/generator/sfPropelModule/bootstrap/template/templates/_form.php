[?php use_stylesheets_for_form($form) ?]
[?php use_javascripts_for_form($form) ?]

[?php echo form_tag_for($form, '@<?php echo $this->params['route_prefix'] ?>', array('class'=>'form form-horizontal form-modal')) ?]
<div class="modal-body">
    [?php include_component('default','flashes') ?]
    [?php echo $form->renderHiddenFields(false) ?]
    [?php foreach ($form as $key=>$field): ?]
        [?php if(!$field->isHidden()){ ?]
          [?php include_partial('<?php echo $this->getModuleName() ?>/form_field_horizontal', array( 'form' => $form, 'key' => $key)) ?]
        [?php } ?]
    [?php endforeach; ?]

</div>
<div class="modal-footer">
    [?php include_partial('<?php echo $this->getModuleName() ?>/form_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?]
</div>
</form>
