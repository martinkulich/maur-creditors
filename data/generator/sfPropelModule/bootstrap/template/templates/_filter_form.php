[?php use_stylesheets_for_form($form) ?]
[?php use_javascripts_for_form($form) ?]


  <form action="[?php echo url_for('@'.sfInflector::underscore($this->getModuleName()).'_filter') ?]" method="post" class="form form-horizontal form-modal">
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
    <ul class="sf_admin_actions">
        <?php //echo $this->addCredentialCondition('[?php echo $helper->linkToReset() ?]') ?>
        <?php echo $this->addCredentialCondition('[?php echo $helper->linkToFilter() ?]') ?>
    </ul>
</div>
  </form>

