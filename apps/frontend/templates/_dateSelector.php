<?php
    $date = date('d.m.Y',strtotime($sf_request->getParameter('date', date('d.m.Y'))));
?>
<div class="date_selector">
    <input type="text" id="datepicker" value="<?php echo $date?>">
</div>
<script>
	$(function() {
		$( "#datepicker" ).datepicker({
			buttonText: 'Choose',
            dateFormat: 'dd.mm.yy',
            onSelect: function(dateText, inst) {
            }
		});
	});
	</script>