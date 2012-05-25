<?php

class CurtTimeZone
{

    protected
    $curt,
    $timeZone,
    $reservation,
    $status,
    $class,
    $title;

    /**
     *
     * @param TimeZone $timeZone
     * @param Curt $curt
     */
    public function __construct(TimeZone $timeZone, Curt $curt)
    {
        $this->curt = $curt;
        $this->timeZone = $timeZone;
    }

    /**
     * @param Reservation $reservation
     */
    public function setReservation(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * @return Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    public function getPrice()
    {
        return $this->timeZone->getPrice();
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }


    public function getClass()
    {
        // prostor pro sirsi logiku
        return $this->status;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

}