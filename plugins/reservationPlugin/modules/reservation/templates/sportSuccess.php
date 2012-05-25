<?php slot('submenu') ?>
<div class="subnav subnav-fixed">
    <div class="container">
        <ul class="nav nav-pills nav-reservation nav-reservation-date pull-left">
            <li>
                <a href="#" id="dp4" data-date-format="dd.mm.yy" data-date="<?php echo format_date($date, 'd')?>"><?php echo format_date($date, 'D')?></a>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#dp4').datepicker({
                         format: 'dd.mm.yy',
                         language: '<?php echo $sf_user->getCulture()?>'
                        })

                        .on('changeDate', function(ev){
                            var date = moment(new Date(ev.date.valueOf()));
                            var url = "<?php echo url_for(sprintf('@reservation_sport?sport_slug=%s&price_category_id=%s&date=', $sportSlug, $priceCategoryId)); ?>";
                            var formatedDate = date.format('YYYY-MM-DD');
                            $(location).attr('href',url+formatedDate);
                            $('#dp4').datepicker('hide');
                        });
                    });
                </script>
            </li>
            <li class="dropdown" id="day_count_select">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#day_count_select">
                    <?php echo __('Show').' '.$dayCounts[$dayCount] ?>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <?php foreach ($dayCounts as $subDayCountKey=>$subDayCount) { ?>
                        <?php if ($subDayCountKey != $dayCount) { ?>
                            <li><?php echo link_to($dayCounts[$subDayCountKey], sprintf('@reservation_sport?sport_slug=%s&date=%s&price_category_id=%s&day_count=%s', $sportSlug, $date, $priceCategoryId, $subDayCountKey)) ?></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </li>
            <?php if ($showSports) { ?>
                <li class="dropdown" id="sport_select">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#sport_select">
                        <?php echo $sport ?>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <?php foreach ($sports as $otherSport) { ?>
                            <?php if ($otherSport->getSlug() != $sport->getSlug()) { ?>
                                <li><?php echo link_to($otherSport, sprintf('@reservation_sport?sport_slug=%s&date=%s&price_category_id=%s&day_count=%s', $otherSport->getSlug(), $date, $priceCategoryId, $dayCount)) ?></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            <?php if ($showPriceCategories) { ?>
                <li class="dropdown" id="price_category_select">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#price_category_select">
                        <?php echo $priceCategory ?>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <?php foreach ($userPriceCategories as $otherPriceCategory) { ?>
                            <?php if ($otherPriceCategory != $priceCategory) { ?>
                                <li><?php echo link_to($otherPriceCategory, sprintf('@reservation_sport?sport_slug=%s&date=%s&price_category_id=%s&day_count=%s', $sportSlug, $date, $otherPriceCategory->getId(), $dayCount)) ?></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>
<?php end_slot() ?>
<?php for ($i = 0; $i < $dayCount; $i++) { ?>
    <?php $dateObject = new DateTime($date);
    $dateObject->modify('+' . $i . ' days') ?>
    <section id ="<?php echo $dateObject->format('Y-m-d') ?>">
        <?php include_component('reservation', 'sportDate', array('sport' => $sport, 'date' => $dateObject->format('Y-m-d'), 'priceCategory' => $priceCategory)) ?>
    </section>
<?php } ?>