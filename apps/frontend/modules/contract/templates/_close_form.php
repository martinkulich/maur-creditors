<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo  url_for('@contract_close?id='.$contract->getId()) ?>" method="post" class="form form-horizontal form-modal">
<div class="modal-body">
    <?php include_component('default','flashes') ?>
    <?php echo $form->renderHiddenFields(false) ?>
    <?php echo $form->renderGlobalErrors() ?>
    <?php $renderOptions = array()?>
    <?php $error = $form['closing_settlement']->hasError() ?  $form['closing_settlement']->getError() : null?>
    <?php if($error && $error instanceof sfValidatorErrorSchema){?>
        <?php foreach($error->getErrors() as $suberror){?>
            <div class="alert alert-error" >
                <a class="close" data-dismiss="alert">×</a>
                <?php echo __($suberror->getMessage(), $error->getArguments()); ?>
            </div>
        <?php }?>
    <?php }?>
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
        <?php echo $helper->linkToSaveOrReuse() ?>
    </ul>
</div>
</form>
