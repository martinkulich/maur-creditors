<div class="modal_content">
    <div class="modal-header" id="reservation_detail_header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo format_date($date, 'D')?></h3>
    </div>
    <form class="form form-horizontal form-modal" method="post" action="<?php echo url_for(sprintf('@reservation_create?sport_slug=%s&date=%s&curt_id=%s&time_zone_id=%s&price_category_id=%s', $sport->getSlug(), $date, $curt->getId(), $timeZone->getId(), $priceCategory->getId())) ?>">
        <?php include_partial('reservation/form', array('form'=>$form, 'reservation'=>$reservation));?>
    </form>
</div>
