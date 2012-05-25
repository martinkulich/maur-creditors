<div class="modal_content">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo format_date($reservation->getDate(), 'D') ?></h3>
    </div>
    <div class="modal-body">
        <div>
            <table class="table table-reservation table-reservation-detail">
                <?php if ($user) { ?>
                    <tr>
                        <th class="name">
                            <?php echo __('Fullname') ?>
                        </th>
                        <td colspan="2">
                            <?php echo $user->getFullname() ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="email">
                            <?php echo __('Email') ?>
                        </th>
                        <td colspan="2">
                            <?php echo $user->getEmail() ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php echo __('Phone') ?>
                        </th>
                        <td colspan="2">
                            <?php echo $user->getPhone() ?>
                        </td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <th class="name">
                            <?php echo __('Fullname') ?>
                        </th>
                        <td colspan="2">
                            <?php echo $reservation->getUserToString() ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="sport">
                        <?php echo __('Sport') ?>
                    </th>
                    <td colspan="2">
                        <?php echo $reservation->getSport()->getName() ?>
                    </td>
                </tr>
                <tr>
                    <th class="price_category">
                        <?php echo __('Price category') ?>
                    </th>
                    <td colspan="2">
                        <?php echo $reservation->getPriceCategory()->getName() ?>
                    </td>
                </tr>
                <tr>
                    <th class="paid">
                        <?php echo __('Paid') ?>
                    </th>
                    <td colspan="2">
                        <?php echo $reservation->getPaid() ? __('Yes') : __('No') ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo __('Note') ?>
                    </th>
                    <td colspan="2 span2">
                        <?php echo $reservation->getNote()?>
                    </td>
                </tr>
                <?php if ($reservationRepeating = $reservation->getReservationRepeating()) { ?>
                    <tr>
                        <th class="repeat_from">
                            <?php echo __('Repeat from') ?>
                        </th>
                        <td colspan="2">
                            <?php echo format_date($reservationRepeating->getRepeatFrom(), 'D') ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="repeat_to">
                            <?php echo __('Repeat to') ?>
                        </th>
                        <td colspan="2">
                            <?php echo format_date($reservationRepeating->getRepeatTo(), 'D') ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div>
            <table class="table table-reservation table-reservation-parts">
                <tr>
                    <th>
                        <?php echo __('Curt') ?>
                    </th>
                    <th>
                        <?php echo __('Time') ?>
                    </th>
                    <th>
                        <?php echo __('Price') ?>
                    </th>
                </tr>
                <?php foreach ($reservation->getOrderedReservationCurts() as $reservationCurt) { ?>
                    <?php $reservation = $reservationCurt->getReservation()?>
                        <tr>
                            <td>
                                <?php echo $reservationCurt->getCurt()->getName() ?>
                            </td>
                            <td>
                                <?php echo sprintf('%s - %s ', $reservation->getFirstTimeZone()->getTimeFrom('H:i'),$reservation->getLastTimeZone()->getTimeTo('H:i')) ?>
                            </td>
                            <td>
                                <?php echo $reservationCurt->getAmount() . ' ' . $defaultCurrency ?>
                            </td>
                        </tr>
                <?php } ?>
                <tr>
                    <th class="total_price" colspan="2">
                        <?php echo __('Total price') ?>
                    </th>
                    <th>
                        <?php echo $reservation->getAmount() . ' ' . $defaultCurrency ?>
                    </th>
                </tr>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <?php echo link_to('<i class="icon-edit icon-white"></i> '.__('Edit'), '@reservation_edit?id=' . $reservation->getId(), array('class' => 'btn btn-primary modal_link')) ?>
        <?php include_partial('reservation/reservationActions', array('reservation' => $reservation)) ?>
    </div>
</div>