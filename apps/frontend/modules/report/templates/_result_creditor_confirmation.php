<?php include_partial('report/result', array('reportType'=>$reportType, 'report'=>$report))?>

<?php if(array_key_exists('year', $filters)){ ?>
    <br />
    <?php include_component('creditor','paidDetail', array( 'filters'=>$filters))?>
<?php } ?>