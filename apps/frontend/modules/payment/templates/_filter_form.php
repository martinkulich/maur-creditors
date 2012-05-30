<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>


  <form action="<?php echo url_for('@'.sfInflector::underscore($this->getModuleName()).'_filter') ?>" method="post" class="form form-horizontal form-modal">
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
    <ul class="sf_admin_actions">
                <?php echo $helper->linkToFilter() ?>    </ul>
</div>
  </form>
<script type="text/javascript">
$(document).ready(function(){
    jQuery('#payment_filters_creditor_id').change();
});
</script>

