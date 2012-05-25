
<?php if($reservation->getId() && ($sf_user->hasCredentialToEditObject('reservation', $reservation->getId())  || $sf_user->hasCredential('reservation.admin'))){?>
    <?php echo link_to('<i class="icon-trash icon-white"></i> '.__('Delete'), '@reservation_delete?id='.$reservation->getId(), array('class'=>'btn btn-danger', 'confirm'=>__('Are you sure? This could not be undone!')))?>
    <?php if($reservation->getReservationRepeatingId()){?>
        <?php echo link_to('<i class="icon-trash icon-white"></i> '.__('Delete all'), '@reservation_delete_all?id='.$reservation->getId(), array('class'=>'btn btn-danger', 'confirm'=>__('Are you sure? This could not be undone!')))?>
    <?php }?>
<?php }?>