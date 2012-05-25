<?php include_partial('report/submenu', array('reportType'=>$reportType, 'hasFilter'=>$hasFilter))?>
<?php if($hasFilter){ ?>
    <?php include_partial('report/result_'.$reportType, array('reportType'=>$reportType, 'data'=>$data))?>
<?php }?>
