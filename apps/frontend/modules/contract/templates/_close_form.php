<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo  url_for('@contract_close?id='.$contract->getId()) ?>" method="post" class="form form-horizontal form-modal">
<div class="modal-body">
    <?php include_component('default','flashes') ?>
    <?php echo $form->renderHiddenFields(false) ?>
    <?php foreach ($form as $key=>$field): ?>
        <?php if(!$field->isHidden()){ ?>
          <?php if($key != 'closing_settlement'){ ?>
            <?php include_partial('contract/form_field_horizontal', array( 'form' => $form, 'key' => $key)) ?>
          <?php }else{ ?>
              <?php include_partial('contract/closing_settlement', array( 'form' => $form)) ?>
          <?php } ?>
        <?php } ?>
    <?php endforeach; ?>

</div>
<div class="modal-footer">
    <ul class="sf_admin_actions">
        <?php echo $helper->linkToSave($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save',)) ?>
        <?php echo $helper->linkToSaveAndReuse($form->getObject(), array(  'params' =>   array(  ),  'class_suffix' => 'save',  'label' => 'Save and reuse',)) ?>
    </ul>
</div>
</form>
