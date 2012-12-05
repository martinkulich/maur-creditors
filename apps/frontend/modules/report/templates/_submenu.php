<?php slot('submenu') ?>
<div class="subnav subnav-fixed no-print">
    <div class="container">
        <ul class="nav nav-pills nav-reservation nav-reservation-date pull-left">
            <li class="sf_admin_action_filters"> <?php echo link_to(__('Filter', array(), 'sf_admin'), '@report_filters?report_type='.$reportType, array('class'=>'modal_link')) ?></li>
            <?php if($hasFilter){?>
                <li class="sf_admin_action_filters"> <?php echo link_to(__('Reset', array(), 'sf_admin'), '@report_reset?report_type='.$reportType, array('class'=>'')) ?></li>
            <?php }?>
                <li class="sf_admin_action_print"><a href=# onclick="window.print();return false;"><?php echo __('Print'); ?></a></li>
        </ul>
    </div>
</div>
<?php end_slot() ?>