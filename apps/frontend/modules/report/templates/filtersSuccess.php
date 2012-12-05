<?php use_helper('I18N', 'Date') ?>
<div class="modal_content">
    <div class="modal-header" >
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('Filter') ?></h3>
    </div>

    <form action="<?php echo url_for('@report_filter?report_type='.$reportType) ?>" method="post" class="form form-horizontal form-modal">
        <div class="modal-body">
            <?php include_component('default','flashes') ?>
            <?php echo $form->renderHiddenFields(true) ?>
            <?php foreach ($form as $key=>$field): ?>
                <?php if(!$field->isHidden()){ ?>
                  <?php include_partial('default/form_field_horizontal', array( 'form' => $form, 'key' => $key)) ?>
                <?php } ?>
            <?php endforeach; ?>
            </div>
            <div class="modal-footer">
            <ul class="sf_admin_actions">
                <li class="sf_admin_action_filter">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-ok icon-white">
                    </i> <?php echo __('Filter', array(), 'sf_admin') ?>
                </button>
            </li>
            </ul>
        </div>
    </form>


</div>
