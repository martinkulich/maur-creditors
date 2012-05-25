<div id="sf_admin_container">

    <div id="schedule">
        <?php foreach($sports as $sport){ ?>
            <div class="sport_link">
                <?php echo link_to($sport->getName(),'@reservation_sport?sport_slug='.$sport->getSlug().'&date='.date('Y-m-d'))?>
            </div>
        <?php } ?>
    </div>
</div>