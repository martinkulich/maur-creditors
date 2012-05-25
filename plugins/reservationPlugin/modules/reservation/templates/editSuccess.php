<div class="modal_content">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo format_date($date, 'D')?></h3>
    </div>
    <form class="form form-horizontal form-modal" method="post" action="<?php echo url_for('@reservation_update?id=' . $reservation->getId()) ?>">
        <?php include_partial('reservation/form', array('form'=>$form, 'reservation'=>$reservation));?>
    </form>
</div>
