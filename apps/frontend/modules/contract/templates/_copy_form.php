<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo  url_for('@contract_duplicate?id='.$contract->getId()) ?>" method="post" class="form form-horizontal form-modal">
<div class="modal-body">
    <?php include_component('default','flashes') ?>
    <?php echo $form->renderHiddenFields(false) ?>
    <?php foreach ($form as $key=>$field): ?>
        <?php if(!$field->isHidden()){ ?>
          <?php include_partial('contract/form_field_horizontal', array( 'form' => $form, 'key' => $key)) ?>
        <?php } ?>
    <?php endforeach; ?>

</div>
<div class="modal-footer">
    <?php include_partial('contract/form_actions', array('contract' => $contract, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
</form>
