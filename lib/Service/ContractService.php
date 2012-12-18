<?php

class ContractService
{

    const DAY_FORMAT = 'd';
    const DATE_FORMAT = 'Y-m-d';

    public function checkContractChanges(Contract $contract)
    {
        $this->checkContractActivation($contract);
        if ($contract->getActivatedAt()) {
            $this->generateSettlementsForContract($contract);
            $this->generateEndOfYearsSettlements($contract);
            $this->updateContractSettlements($contract);
        }
    }

    public function checkContractActivation(Contract $contract)
    {
        $paymentsAmount = $contract->getPaymentsAmount();
        if ($paymentsAmount >= $contract->getAmount()) {
            $activatedAt = $contract->getActivatedAt() ? new DateTime($contract->getActivatedAt()) : null;
            $activationDate = $this->getActivationDate($contract);
            if ($activatedAt != $activationDate) {
                $contract->setActivatedAt($activationDate);
                $translateService = ServiceContainer::getTranslateService();

                if (!$activatedAt) {
                    $success = $translateService->__('Contract activated');
                } else {
                    $format = 'd.m.Y';
                    $success = $translateService->__('Contract activation changed from: %from% to: %to%', array('%from%' => $activatedAt->format($format), '%to%' => $activationDate->format($format)));
                }
                ServiceContainer::getMessageService()->addSuccess($success);
                $contract->save();
            }
        }
    }

    protected function getActivationDate(Contract $contract)
    {
        $activationDate = null;
        $lastPayment = $contract->getLastPayment();
        if ($lastPayment) {
            $lastPaymentDate = new DateTime($lastPayment->getDate());
            $contractSignedDate = new DateTime($contract->getCreatedAt());
            $activationDate = $lastPaymentDate > $contractSignedDate ? $lastPaymentDate : $contractSignedDate;
//            $activationDate->modify('+1 day');
        }

        if ($activationDate === null) {
            throw new Exception('Contract could not be activated');
        }

        return $activationDate;
    }

    public function updateContractSettlements(contract $contract)
    {
        foreach ($contract->getSettlements() as $settlement) {
            if ($contract->getClosedAt()) {
                $closedAt = new DateTime($contract->getClosedAt());
                $settlementDate = new DateTime($settlement->getDate());
                if ($closedAt < $settlementDate && !in_array($settlement->getSettlementType(), array(SettlementPeer::CLOSING, SettlementPeer::CLOSING_BY_REACTIVATION, SettlementPeer::MANUAL))) {
                    $settlement->delete();
                    continue;
                }
            }

            if (!$settlement->getManualBalance()) {
                $settlement->setBalance($this->getBalanceForSettlement($settlement));
            }

            if (!$settlement->getManualInterest()) {
                $settlement->setInterest($this->getInterestForSettlement($settlement));
            }

            $settlement->save();
            $settlement->reload();
        }
    }

    public function generateSettlementsForContract(Contract $contract)
    {
        $nextSettlementDate = $this->getNextSettlementDateForContract($contract);
        $lastDayOfNextYear = new DateTime();
        $lastDayOfNextYear->setDate($lastDayOfNextYear->format('Y') + 1, '12', '31');
        $closedAt = null;
        if ($contract->getClosedAt()) {
            $closedAt = new DateTime($contract->getClosedAt());
        }

        if ((!$closedAt || $closedAt && $closedAt > $nextSettlementDate) && ($nextSettlementDate < $lastDayOfNextYear)) {
            $generate = true;
            if ($settlement = $contract->getSettlementForDate($nextSettlementDate)) {
                if ($settlement->getSettlementType() == SettlementPeer::END_OF_YEAR) {
                    $settlement->delete();
                } else {
                    $generate = false;
                }
            }
            if ($generate) {
                $newSettlement = $this->addSettlementForContract($contract, SettlementPeer::IN_PERIOD, $nextSettlementDate);
                $this->generateSettlementsForContract($contract);
            }
        }
    }

    public function generateEndOfYearsSettlements(Contract $contract)
    {
        foreach ($this->getYearsOfContract($contract) as $year) {
            $lastDateOfYear = new DateTime($year . '-12-30');
            if (!$contract->getSettlementForDate($lastDateOfYear)) {
                $this->addSettlementForContract($contract, SettlementPeer::END_OF_YEAR, $lastDateOfYear);
            }
        }
    }

    public function addEndOfFirstyearSettlementForContractIfNotExists(Contract $contract)
    {
        $contractActivatedAt = $contract->getActivatedAt();
        if (!$contractActivatedAt) {
            throw new Exception('Contract is not activated');
        }

        $activatedAtDate = new DateTime($contractActivatedAt);
        $endOfFirstYearDate = new DateTime($activatedAtDate->format('Y-12-30'));

        $criteria = new Criteria();
        $criteria->add(SettlementPeer::DATE, $endOfFirstYearDate);
        $criteria->add(SettlementPeer::SETTLEMENT_TYPE, SettlementPeer::END_OF_FIRST_YEAR);
        $endOfFirstyearSettlement = $contract->getSettlements($criteria);
        if (!$endOfFirstyearSettlement) {
            $endOfFirstyearSettlement = $this->addSettlementForContract($contract, SettlementPeer::END_OF_FIRST_YEAR, $endOfFirstYearDate);
        }

        return $endOfFirstyearSettlement;
    }

    protected function getYearsOfContract(Contract $contract)
    {
        $years = array();
        if ($contract->getActivatedAt()) {
            $yearFormat = 'Y';
            $activatedAt = new DateTime($contract->getActivatedAt());
            $year = $firstYear = (integer) $activatedAt->format($yearFormat);
            $today = new DateTime('now');
            $currentYear = $today->format($yearFormat);
            while ($year <= $currentYear) {
                $years[] = $year;
                $year++;
            }
        }

        return $years;
    }

    public function addSettlementForContract(Contract $contract, $settlementType = SettlementPeer::IN_PERIOD, DateTime $date = null)
    {
        $settlement = new Settlement();
        $settlement->setContract($contract);
        if ($date === null) {
            $date = $this->getNextSettlementDateForContract($contract);
        }

        $settlement->setDate($date);
        $settlement->setBalance($this->getBalanceForSettlement($settlement));
        $settlement->setInterest($this->getInterestForSettlement($settlement));
        $settlement->setBankAccount($contract->getCreditor()->getBankAccount());
        $settlement->setSettlementType($settlementType);
        $settlement->save();

        $contract->addSettlement($settlement);
        $contract->reload();

        $this->updateContractSettlements($contract);

        return $settlement;
    }

    public function getNextSettlementDateForContract(Contract $contract)
    {
        if (($activatedAt = $contract->getActivatedAt()) == null) {
            throw new Exception('Contract was not activated');
        }
        $lastSettlement = $contract->getLastSettlement(SettlementPeer::IN_PERIOD);
        if (!$lastSettlement) {
//            $lastSettlement = $contract->getLastSettlement(SettlementPeer::END_OF_YEAR);
        }
        $nextSettlementDate = null;
        if ($lastSettlement) {
            $isFirst = false;
            $previousDate = new DateTime($lastSettlement->getDate());
        } else {
            $isFirst = true;
            $firstDate = $contract->getFirstSettlementDate();
            if ($firstDate) {
                $nextSettlementDate = new DateTime($firstDate);
            } else {
                $previousDate = new DateTime($activatedAt);
            }
        }

        if (!$nextSettlementDate) {
            $nextSettlementYear = $previousDateYear = $previousDate->format('Y');
            $nextSettlementMonth = $previousDateMonth = $previousDate->format('m');
            $nextSettlementDay = $previousDateDay = $previousDate->format(self::DAY_FORMAT);

            $nextSettlementMonth = $previousDateMonth + $contract->getPeriodInMonths();
            if ($nextSettlementMonth > 12) {
                $nextSettlementMonth -= 12;
                $nextSettlementYear +=1;
            }

            $nextSettlementDateMonthFirstDate = new DateTime(date(self::DATE_FORMAT, mktime(0, 0, 0, $nextSettlementMonth, 1, $nextSettlementYear)));
            $nextSettlementDateMonthLastDate = clone $nextSettlementDateMonthFirstDate;
            $nextSettlementDateMonthLastDate->modify('last day of this month');
            $nextSettlementDateMonthLastDateDay = $nextSettlementDateMonthLastDate->format(self::DAY_FORMAT);
            $nextSettlementDay = $nextSettlementDay > $nextSettlementDateMonthLastDateDay ? $nextSettlementDateMonthLastDateDay : $nextSettlementDay;
            $nextSettlementDate = new DateTime(date(self::DATE_FORMAT, mktime(0, 0, 0, $nextSettlementMonth, $nextSettlementDay, $nextSettlementYear)));


            if ($isFirst) {
                $nextSettlementDate->modify('-1 day');
            }
        }

        if ($nextSettlementDate->format(self::DAY_FORMAT) == 31) {
            $nextSettlementDate->modify('-1 day');
        }

        return $nextSettlementDate;
    }

    public function getBalanceForSettlement(Settlement $settlement)
    {
        $contract = $settlement->getContract();
        $previousSettlement = $settlement->getPreviousSettlementOfContract();
        if ($previousSettlement) {
            $balance = $previousSettlement->getBalance();
            $balance += $previousSettlement->getCapitalized() - $previousSettlement->getBalanceReduction();
        } else {
            $balance = $contract->getAmount();
        }
        return $balance;
    }

    public function getInterestForSettlement(Settlement $settlement)
    {
        $contract = $settlement->getContract();
        $interest = 0;
        if (!$contract->getClosedAt() || ($contract->getClosedAt() && $contract->getClosedAt() >= $settlement->getDate())) {
            $balance = $settlement->getBalance();
            if (!$balance) {
                $balance = $this->getBalanceForSettlement($settlement);
            }

            $interest = $balance * $contract->getInterestRate() / 100 * $this->getDaysCount($settlement) / 360;
            $criteria = new Criteria();
            $criteria->add(SettlementPeer::DATE, $settlement->getDate());
            $criteria->add(SettlementPeer::ID, $settlement->getId(), Criteria::NOT_EQUAL);
            $otherSettlements = $contract->getSettlements($criteria);

            foreach ($otherSettlements as $settlement) {
                $interest -= $settlement->getInterest();
            }
        }
        return $interest;
    }

    public function getDaysDiff(DateTime $dateFrom, DateTime $dateTo)
    {
        $yearDiff = $dateTo->format('Y') - $dateFrom->format('Y');
        $monthDiff = $dateTo->format('m') - $dateFrom->format('m');
        $dayFrom = $dateFrom->format(self::DAY_FORMAT);
        $dayFrom = $dayFrom == 31 ? 30 : $dayFrom;
        $dayTo = $dateTo->format(self::DAY_FORMAT);
        $dayTo = $dayTo == 31 ? 30 : $dayTo;
        $dayDiff = $dayTo - $dayFrom;

        return $yearDiff * 360 + $monthDiff * 30 + $dayDiff;
    }

    public function getDaysCount(Settlement $settlement)
    {

        $daysCount = $this->getDaysDiff($this->getPreviousDateForSettlement($settlement), new DateTime($settlement->getDate()));

        if ($settlement->isFirstOfContract()) {
            $daysCount +=1;
        }

        if ($settlement->getSettlementType() == SettlementPeer::CLOSING) {
            $daysCount -=1;
        }
        return $daysCount;
    }

    /**
     * @param Settlement $settlement
     * @return DateTime
     */
    protected function getPreviousDateForSettlement(Settlement $settlement)
    {
        $previousSettlement = $settlement->getPreviousSettlementOfContract();
        if ($previousSettlement) {
            $previousDate = $previousSettlement->getDate();
        } else {
            $previousDate = $settlement->getContract()->getActivatedAt();
        }

        return new DateTime($previousDate);
    }

    /**
     * @param Contract $contract
     */
    public function getContractClosingAmount(Contract $contract, Datetime $date = null, $settlementType = SettlementPeer::CLOSING)
    {

        $clossingSettlement = null;
        if ($date == null) {
            $clossingSettlement = $contract->getLastSettlement(SettlementPeer::CLOSING);
            $date = $clossingSettlement ? new Datetime($clossingSettlement->getDate()) : new DateTime('now');
        }


        $unsettled = $contract->getUnsettled($date);
        if (!$clossingSettlement) {
            $settlement = null;
            if ($date) {
                $criteria = new Criteria();
                $criteria->add(SettlementPeer::DATE, $date);
                $settlementWithSameDate = $contract->getSettlements($criteria);
                if (count($settlementWithSameDate) > 0) {
                    $settlement = reset($settlementWithSameDate);
                    $previousSettlement = $settlement->getPreviousSettlementOfContract();
                    if ($previousSettlement) {
                        $unsettled = $contract->getUnsettled(new DateTime($previousSettlement->getDate()));
                    }
                }
            }
            if (!$settlement) {
                $settlement = new Settlement();
                $settlement->setContract($contract);
                $settlement->setDate($date);
            }
            $settlement->setSettlementType($settlementType);
            $settlement->setBalance($this->getBalanceForSettlement($settlement));
            $interest = $this->getInterestForSettlement($settlement);
            $settlement->setInterest($interest);
            $unsettled += $settlement->getUnsettled();
        } else {
            $settlement = $clossingSettlement;
        }
        $closingAmount = array(
            'unsettled' => $unsettled,
            'balance_reduction' => $settlement->getBalance() - $settlement->getBalanceReduction(),
        );

        return $closingAmount;
    }

}
