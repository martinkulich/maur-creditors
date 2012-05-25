<?php

class reservationPluginComponents extends sfComponents
{

    public function executeSportDate(sfWebRequest $request)
    {
        $scheduleService = ServiceContainer::getScheduleService();

        $currentPlayground = ServiceContainer::getPlaygroundService()->getCurrentPlayground();

        $this->schedules = $scheduleService->getSchedulesForDate(new DateTime($this->date), $this->sport);

        $this->curts = array();
        foreach ($this->schedules as $schedule) {
            foreach ($schedule->getCurts() as $curt) {
                $this->curts[$curt->getId()] = $curt;
            }
        }

        if (count($this->curts) == 0) {
            $i18 = sfContext::getInstance()->getI18N();
            sfProjectConfiguration::getActive()->loadHelpers('Date');
            $error = $i18->__('There is no curt for %sport%  at %date%', array('%sport%' => $this->sport->getName(), '%date%' => format_date($this->date, 'D')));
            ServiceContainer::getMessageService()->addError($error);
        }

        $this->priceAmounts = ServiceContainer::getPriceService()->getPriceAmounts();

        if (!isset($this->priceCategory)) {
            throw new exception('Unknow price category');
        }
        $this->priceCategories = $currentPlayground->getPriceCategorys();
        $reservationService = ServiceContainer::getReservationService();

        $this->times = $scheduleService->getTimesForSport($this->sport, new DateTime($this->date));

        $this->period = $scheduleService->getPeriodForSport($this->sport, new DateTime($this->date));
        $this->minTime = $scheduleService->getMinTimeForSport($this->sport, new DateTime($this->date));
        $this->maxTime = $scheduleService->getMaxTimeForSport($this->sport, new DateTime($this->date));
        $this->periods = array();
        $this->curtsTimes = array();

        foreach ($this->schedules as $schedule) {
            $schedulePeriod = $schedule->getPeriod();
            $scheduleTimes = array();
            $time = $this->minTime;
            $timeFormat = 'H:i';
            while ($time < $this->maxTime) {
                $scheduleTimes[] = date($timeFormat, $time);
                $time += $schedulePeriod;
            }
            foreach ($schedule->getCurts() as $curt) {
                $curtId = $curt->getId();
                $this->periods[$curtId] = $schedulePeriod;
                $this->curtsTimes[$curtId] = $scheduleTimes;
            }
        }

        $translationService = ServiceContainer::getTranslateService();

        $unavailableTitle = $translationService->__('Unavailable');
        $occupiedTitle = $translationService->__('Occupied');
        $reservedTitle = $translationService->__('Reserved');

        $this->curtTimes = array();
        foreach ($this->curts as $curtId => $curt) {
            foreach ($this->times as $timeKey => $time) {
                $curtTime = new CurtTime($curt, $time);
                $this->curtTimes[$curtId][$timeKey] = $curtTime;
            }
        }

        $priceCategoryId = $this->priceCategory->getId();
        $prices = ServiceContainer::getPriceService()->getPricesForPlayground();
        foreach ($this->curts as $curtId => $curt) {

            foreach ($scheduleService->getTimeZonesForCurt($curt, new DateTime($this->date)) as $timeZone) {

                $curtTime = $this->curtTimes[$curtId][$timeZone->getTimeFrom('H:i')];
                $curtTime->setTimeZone($timeZone);
                if (!$timeZone->getPriceId()) {
                    $curtTime->setStatus('unavailable');
                    $curtTime->setTitle($unavailableTitle);
                } else {
                    $price = $prices[$timeZone->getPriceId()];
                    $priceAmount = $this->priceAmounts[$price->getId()][$priceCategoryId]->getAmount();
                    $title = sprintf('%s (%s)', $price->getName(), $priceAmount);
                    $curtTime->setTitle($title);
                }
            }
        }
        $userHasReservationAdminCredential = sfContext::getInstance()->getUser()->hasCredential('reservation.admin');

        $reservationCriteria = new Criteria();
        $reservationCriteria->add(ReservationPeer::SCHEDULE_ID, array_keys($this->schedules), Criteria::IN);
        $reservationCriteria->add(ReservationPeer::DATE, $this->date);
        $this->reservations = $reservationService->getReservationsForDateAndCurts(new DateTime($this->date), array_keys($this->curts));
        $this->reservationTimeZonesCounts = array();
        foreach ($this->reservations as $reservation) {
            $this->reservationTimeZonesCounts[$reservation->getId()] = 0;
        }

        $reservationCurtsCriteria = new Criteria();
        $reservationCurtsCriteria->add(ReservationCurtPeer::CURT_ID, array_keys($this->curts), Criteria::IN);
        $reservationCurtsCriteria->addJoin(ReservationCurtPeer::RESERVATION_ID, ReservationPeer::ID);
        $reservationCurtsCriteria->add(ReservationPeer::ID, array_keys($this->reservations), Criteria::IN);

        $this->reservationCurts = array();
        foreach (ReservationCurtPeer::doSelect($reservationCurtsCriteria) as $reservationCurt) {
            $reservationCurtReservationId = $reservationCurt->getReservationId();
            if (!array_key_exists($reservationCurtReservationId, $this->reservationCurts)) {
                $this->reservationCurts[$reservationCurtReservationId] = array();
            }
            $this->reservationCurts[$reservationCurtReservationId][$reservationCurt->getCurtId()] = $reservationCurt;
        }

        $this->firstReservationTimeZones = array();
        $this->reservationAmounts = array();
        $this->reservationSaleAmounts = array();

        foreach ($reservationService->getReservationTimeZonesForDateAndCurts(new DateTime($this->date), array_keys($this->curts)) as $reservationTimeZone) {
            $reservationId = $reservationTimeZone->getReservationId();
            $this->reservationTimeZonesCounts[$reservationId] += 1;
            if (!array_key_exists($reservationId, $this->firstReservationTimeZones)) {
                $this->firstReservationTimeZones[$reservationId] = $reservationTimeZone;
            }

            $reservation = $this->reservations[$reservationId];
            $timeZone = $reservationTimeZone->getTimeZone();
            if (!array_key_exists($reservationId, $this->reservationAmounts)) {
                $this->reservationAmounts[$reservationId] = 0;
            }

            if (!array_key_exists($reservationId, $this->reservationSaleAmounts)) {
                $this->reservationSaleAmounts[$reservationId] = 0;
            }

            $this->reservationAmounts[$reservationId] += $reservationTimeZone->getAmount();
            $this->reservationSaleAmounts[$reservationId] += $reservationTimeZone->getSaleAmount();

            foreach ($this->reservationCurts[$reservationId] as $reservationCurt) {
                $curtId = $reservationCurt->getCurtId();
                if (array_key_exists($curtId, $this->curts)) {
                    $curtTime = $this->curtTimes[$curtId][$timeZone->getTimeFrom('H:i')];
                    if ($curtTime) {
                        $curtTime->setReservationTimeZone($reservationTimeZone);
                        $curtTime->setStatus('occupied');

                        if ($reservation->getSportId() != $this->sport->getId()) {
                            $curtTime->setStatus('other_sport');
                            $curtTime->setTitle($unavailableTitle);
                        }
                    }
                }
            }
        }


        $user = $this->getUser()->getSecurityUser();
        if ($user) {
            foreach ($reservationService->getReservationTimeZonesForDateSportAndUser(new DateTime($this->date), $this->sport, $user) as $reservationTimeZone) {
                foreach ($this->reservationCurts[$reservationTimeZone->getReservationId()] as $reservationCurt) {
                    $curtId = $reservationCurt->getCurtId();
                    if (array_key_exists($curtId, $this->curtTimes)) { # rozvrh muze byt neverejny (uzivatel ho nemusi videt, prestoze tam ma rezervaci
                        $curtTime = $this->curtTimes[$reservationCurt->getCurtId()][$reservationTimeZone->getTimeZone()->getTimeFrom('H:i')];
                        if ($curtTime) {
                            $curtTime->setReservationTimeZone($reservationTimeZone);
                            $curtTime->setStatus('reserved');
                            $curtTime->setTitle($reservedTitle);
                        }
                    }
                }
            }
        }

        foreach ($this->curtTimes as $curtId => $times) {
            foreach ($times as $timeKey => $curtTime) {
                if (!$curtTime->getTimeZone()) {
                    $curtTime->setStatus('unavailable');
                    $curtTime->setTitle($unavailableTitle);
                }
            }
        }

        $this->sportSlug = $this->sport->getSlug();

        $this->userHasCredentialToEditReservations = sfContext::getInstance()->getUser()->hasCredential('reservation.admin');
        $this->userId = $this->getUser()->getId();

        $this->defaultCurrency = 'Kč';
    }
}
