<?php if(count($curts)>0){?>
    <?php $userId = $sf_user->getId();?>
    <table class="table table-bordered table-reservation">
        <tr>
            <th class="date"><?php echo format_date($date,'EEEE')?> <?php echo format_date($date,'dd.MM.')?></th>
            <?php foreach($times as $time){?>
            <th class="time">
            <?php echo sprintf('%s', $time['from'])?>
            </th>
            <?php }?>
        </tr>
            <?php foreach($curts as $curt){?>
            <tr>
                <th class="curt">
                    <?php echo $curt->getName(); $curtId = $curt->getId()?>
                </th>
                <?php foreach($times as $timeKey => $time){?>
                    <?php
                        $curtTime = $curtTimes[$curtId][$timeKey];
                        $class = $curtTime->getClass();
                        $timeZone = $curtTime->getTimeZone();
                        $status = $curtTime->getStatus();
                        $price = $curtTime->getPrice();
                        $reservationTimeZone = $curtTime->getReservationTimeZone();

                        $colspan = $periods[$curtId]/$period;

                        $priceFactor = 3600/$periods[$curtId];
                        if($price)
                        {
                            $priceAmount = (integer) $priceAmounts[$price->getId()][$priceCategory->getId()]->getAmount() / $priceFactor;
                        }

                        $showCell = in_array($timeKey, $curtsTimes[$curtId]);

                        if($reservationTimeZone)
                        {
                            $reservationTimeZoneReservation = $reservations[$reservationTimeZone->getReservationId()];
                            $firstReservationTimeZone = $firstReservationTimeZones[$reservationTimeZoneReservation->getId()];
                            $firstReservationTimeZoneId = $firstReservationTimeZone->getId();
                            $reservationTimeZoneReservation = $reservations[$reservationTimeZoneReservation->getId()];
                            $reservationTimeZoneReservationId = $reservationTimeZoneReservation->getId();

                            if($reservationTimeZone->getId() == $firstReservationTimeZoneId)
                            {
                                $colspan = $colspan * $reservationTimeZonesCounts[$reservationTimeZoneReservation->getId()];
                            }
                             else {
                                 $showCell = false;
                            }
                        }

                    ?>
                    <?php if($showCell){?>
                        <td
                        class="curt_time_zone
                            <?php if($reservationTimeZone){ ?>
                                <?php echo ' well reserved ' ?>
                                <?php if($reservationTimeZoneReservation->getReservationRepeatingId() && $userHasCredentialToEditReservations)  echo ' repeated '  ?>
                                <?php if($userHasCredentialToEditReservations && $reservationTimeZoneReservation->getPaid())  echo ' paid '  ?>
                                <?php if(($color = $priceCategories[$reservationTimeZoneReservation->getPriceCategoryId()]->getColor()) && $userHasCredentialToEditReservations){?>
                                    <?php echo ' '.$color.' '?>
                                <?php } ?>
                            <?php }?>
                            <?php echo $class?>"
                        colspan="<?php echo $colspan?>"
                        nowrap="nowrap">
                            <?php if($price && $status != 'unavailable'){?>
                                <?php if(!$reservationTimeZone){?>
                                        <?php
                                            echo link_to(
                                                $priceAmount,
                                                '@reservation_new?curt_id='.$curt->getId().'&sport_slug='.$sportSlug.'&date='.$date.'&time_zone_id='. $timeZone->getId().'&price_category_id='.$priceCategory->getId(),
                                                array(
                                                    'class'=>"modal_link",
                                                )
                                            );
                                        ?>

                                <?php }else{?>
                                    <?php if($userHasCredentialToEditReservations || ($userId && $reservationTimeZoneReservation->getUserId() == $userId)) {?>
                                        <?php $username = $reservationTimeZoneReservation->getUserSurname(5*$colspan) ?>
                                        <?php if(($userHasCredentialToEditReservations || ($userId && $reservationTimeZoneReservation->getCreatedByUserId() == $userId)) && $reservationTimeZoneReservation->getSportId() == $sport->getId()) {?>
                                            <?php
                                                echo link_to(
                                                    $username,
                                                    '@reservation_detail?id='.$reservationTimeZoneReservationId,
                                                    array(
                                                        'class'=>"modal_link reservation_detail_link",
                                                        'amount'=>$reservationAmounts[$reservationTimeZoneReservation->getId()] + $reservationSaleAmounts[$reservationTimeZoneReservation->getId()],
                                                        'username'=>$username,
                                                        )
                                            );
                                            ?>
                                        <?php }else{?>
                                            <?php echo $username?>
                                        <?php }?>
                                    <?php }?>
                                <?php }?>
                            <?php }?>
                        </td>
                    <?php }?>
                <?php }?>
            </tr>
        <?php }?>
    </table>
<?php }?>