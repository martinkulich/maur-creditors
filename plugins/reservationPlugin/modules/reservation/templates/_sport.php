<?php $userId = $sf_user->getId();?>
<div id="schedule_table">
    <table>
        <tr>
        <th></th>
        <?php foreach($times as $time){?>
                <th class="time">
                    <?php echo sprintf('%s', $time['from'])?>
                </th>
            <?php }?>
        </tr>
        <?php foreach($curts as $curt){?>
            <tr>
                <th class="curt">
                    <?php echo $curt->getName(); $curtId = $curt->getId();?>
                </th>
                <?php foreach($timeZones[$curtId] as $timeZone){?>
                    <?php
                        $curtTimeZone = $curtTimeZoneCollection->getCurtTimeZone($timeZone, $curt);
                        $title = $curtTimeZone->getTitle();
                        $class = $curtTimeZone->getClass();
                        $price = $curtTimeZone->getPrice();
                        if($price)
                        {
                            $priceWithCurrency = $price->getAmount().' '.$defaultCurrency;
                        }
                        $reservation = $curtTimeZone->getReservation();
                        $status = $curtTimeZone->getStatus();
                     ?>
                    <td
                        <?php if($price && !$class){?>
                                style="background-color: <?php echo $price->getColor();?>"
                        <?php  }?>

                        class="curt_time_zone <?php echo $class?>"
                    >
                        <?php if($price && $status != 'unavailable'){?>
                            <?php if(!$reservation){?>
                                    <?php echo link_to($priceWithCurrency, '@reservation_create?curt_id='.$curt->getId().'&sport_slug='.$sportSlug.'&date='.$date.'&time_zone_id='. $timeZone->getId());?>
                            <?php }else{?>
                                <?php if(!$reservation){?>
                                    <?php  if($userHasCredentialToEditReservations) {?>
                                    <?php
                                    echo link_to(
                                        $reservation->getUserSurname(10),
                                        '@reservation_edit?id='.$reservation->getId()
                                            ,array(
                                                'class' => 'with_cluetip',
                                                'rel' => url_for('@reservation_detail?id='.$reservation->getId())
                                            )
                                        );
//                                    ?>
                                    <?php  }?>
                                <?php  }else{?>
                                <?php  }?>
                            <?php }?>
                        <?php }?>
                    </td>
                <?php }?>
            </tr>
        <?php }?>
    </table>
</div>