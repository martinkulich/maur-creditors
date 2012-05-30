<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php echo form_tag_for($form, '@payment', array('class'=>'form form-horizontal form-modal')) ?>
<div class="modal-body">
    <?php include_component('default','flashes') ?>
    <?php echo $form->renderHiddenFields(false) ?>
    <?php foreach ($form as $key=>$field): ?>
        <?php if(!$field->isHidden()){ ?>
          <?php include_partial('payment/form_field_horizontal', array( 'form' => $form, 'key' => $key)) ?>
        <?php } ?>
    <?php endforeach; ?>

</div>
<div class="modal-footer">
    <?php include_partial('payment/form_actions', array('payment' => $payment, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    jQuery('#payment_creditor_id').change();
});
</script>
