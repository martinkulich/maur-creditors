<?php echo $widget->render(sprintf('%s[contract_id]', $formName), $contractId);?>
<script type="text/javascript">
    $(document).ready(function(){
        jQuery('#<?php echo in_array($formName , array('allocation', 'allocation_filters')) ? $formName : ""?>_contract_id').change();
    });
</script>