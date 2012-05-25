<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php echo form_tag_for($form, '@regulation', array('class'=>'form form-horizontal form-modal')) ?>
<div class="modal-body">
    <?php include_component('default','flashes') ?>
    <?php echo $form->renderHiddenFields(false) ?>
    <?php foreach ($form as $key=>$field): ?>
        <?php if(!$field->isHidden()){ ?>
          <?php include_partial('regulation/form_field_horizontal', array( 'form' => $form, 'key' => $key)) ?>
        <?php } ?>
    <?php endforeach; ?>

</div>
<div class="modal-footer">
    <?php include_partial('regulation/form_actions', array('regulation' => $regulation, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
</form>
