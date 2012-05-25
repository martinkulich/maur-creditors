<?php

class CurtTimeZoneCollection
{

    protected $curtTimeZones = array();

    /**
     * @param curtTimeZone $curtTimeZone 
     */
    public function addCurtTimeZone(TimeZone $timeZone, Curt $curt)
    {
        $curtTimeZoneKey = $this->getCurtTimeZoneKey($timeZone, $curt);
        if (array_key_exists($curtTimeZoneKey, $this->curtTimeZones)) {
            
            throw new exception(sprintf('Curt time zone %s already added', $curtTimeZoneKey));
        }

        $curtTimeZone = new CurtTimeZone($timeZone, $curt);
        $this->curtTimeZones[$curtTimeZoneKey] = $curtTimeZone;

        return $curtTimeZone;
    }

    public function getCurtTimeZone(TimeZone $timeZone, Curt $curt, $throwExceptionIfNotExists = false)
    {
        $curtTimeZoneKey = $this->getCurtTimeZoneKey($timeZone, $curt);

        $curtTimeZone = null;
        if (!array_key_exists($curtTimeZoneKey, $this->curtTimeZones)) {
            if ($throwExceptionIfNotExists) {
                throw new exception(sprintf('Curt time zone %s do not exists', $curtTimeZoneKey));
            }
        } else {
            $curtTimeZone = $this->curtTimeZones[$curtTimeZoneKey];
        }

        return $curtTimeZone;
    }

    public function getCurtTimeZoneKey(TimeZone $timeZone, Curt $curt)
    {
        return $curt->getId() . '-' . $timeZone->getTimeFrom('H:i');
    }

}