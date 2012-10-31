<?php slot('submenu') ?>
<div class="subnav subnav-fixed">
    <div class="container">
        <ul class="nav nav-pills nav-reservation nav-reservation-date pull-left">
            <li class="sf_admin_action_filters"> <?php echo link_to(__('Filter', array(), 'sf_admin'), '@report_filters?report_type='.$reportType, array('class'=>'modal_link')) ?></li>
            <?php if($hasFilter){?>
                <li class="sf_admin_action_filters"> <?php echo link_to(__('Reset', array(), 'sf_admin'), '@report_reset?report_type='.$reportType, array('class'=>'')) ?></li>
            <?php }?>
        </ul>
    </div>
</div>
<?php end_slot() ?>