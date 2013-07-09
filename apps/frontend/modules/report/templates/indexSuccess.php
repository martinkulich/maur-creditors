<?php include_partial('report/submenu', array('reportType'=>$reportType, 'hasFilter'=>$hasFilter))?>
<?php if('creditor_confirmation' === $reportType){ ?>
    <?php include_partial('report/result_creditor_confirmation', array('reportType'=>$reportType, 'report'=>$report,'filters'=>$filters))?>
<?php }else{ ?>
    <?php include_partial('report/result', array('reportType'=>$reportType, 'report'=>$report, 'filters'=>$filters))?>
<?php } ?>
