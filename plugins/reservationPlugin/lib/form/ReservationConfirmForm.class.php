<?php

class ReservationConfirmForm extends reservationForm
{

    public function configure()
    {
        parent::configure();
        $this->unsetField('user_id');
    }

    public function doSave($con = null)
    {
        $securityUser = sfContext::getInstance()->getUser()->getSecurityUser();
        $reservation = $this->getObject();
        $reservation->setSecurityUserRelatedByUserId($securityUser);
        parent::doSave($con);
        $reservation->setUserString($securityUser->getFullName());
        $reservation->save($con);
    }

}
