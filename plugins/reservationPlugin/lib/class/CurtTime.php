<?php

class CurtTime
{
    protected
    $curt,
    $time,
    $timeZone,
    $reservationTimeZone,
    $status,
    $class,
    $title,
    $price;

    /**
     *
     * @param TimeZone $timeZone
     * @param Curt $curt
     */
    public function __construct(Curt $curt, $time)
    {
        $this->curt = $curt;
        $this->time = $time;
    }

    /**
     * @param TimeZone $timeZone
     */
    public function setTimeZone(TimeZone $timeZone)
    {
        $this->timeZone = $timeZone;
    }

    /**
     * @param Reservation $reservationTimeZone
     */
    public function setReservationTimeZone(reservationTimeZone $reservationTimeZone)
    {
        $this->reservationTimeZone = $reservationTimeZone;
    }

    public function getTimeZone()
    {
        $timeZone = null;
        if (!is_null($this->timeZone)) {
            $timeZone = $this->timeZone;
        } elseif (!is_null($this->reservationTimeZone)) {
            $timeZone = $this->reservationTimeZone->getTimeZone();
        }

        return $timeZone;
    }

    /**
     * @return ReservationTimeZone
     */
    public function getReservationTimeZone()
    {
        return $this->reservationTimeZone;
    }

    public function getPrice()
    {
        $price = null;
        if (!is_null($this->price)) {
            $price = $this->price;
        } else {
            if (isset($this->timeZone)) {
                $this->price = $price = $this->timeZone->getPrice();
            }
        }

        return $price;
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
        $class = $this->status;
        $price = $this->getPrice();
        if ($price && !$this->reservationTimeZone) {
            $class .= ' text-' . $price->getColor() . ' ';
        }
        return $class;
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