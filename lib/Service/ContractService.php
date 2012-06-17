<?php

class ContractService
{

//    public function checkSettlements()
//    {
//        $this->checkSettlementsInPeriod();
//        $this->checkSettlementsEndOfFirstYear();
//    }
//
//    public function checkSettlementsInPeriod()
//    {
//        $query = "
//            select id from contract c where activated_at IS NOT NULL AND
//                (
//                    (
//                         (((select max(s.date) from settlement s where s.contract_id = c.id) + (12/c.period || ' month')::interval) <= now()::DATE)
//                        AND
//                        ((select count(*) from settlement s where  s.contract_id = c.id) > 0)
//                    )
//                    OR
//                    (
//                        (c.activated_at + (12/c.period || ' month')::interval <= now()::DATE)
//                        AND
//                        ((select count(*) from settlement s where  s.contract_id = c.id) = 0)
//                    )
//
//                )";
//
//        $connection = Propel::getConnection();
//
//        $statement = $connection->prepare($query);
//        $statement->execute();
//        $contractIds = array();
//        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
//            $contractIds[] = $row['id'];
//        }
//
//        $criteria = new Criteria();
//        $criteria->add(ContractPeer::ID, $contractIds, Criteria::IN);
//        $today = new DateTime('now');
//        foreach (ContractPeer::doSelect($criteria) as $contract) {
//            $newSettlement = $this->generateSettlementsForContract($contract);
//        }
//    }
//
//    public function checkSettlementsEndOfFirstYear()
//    {
//        $query = "
//            select id from contract c where activated_at IS NOT NULL AND
//                    (
//                        (
//                            select count(*) from settlement s where  s.contract_id = c.id AND s.date = (year(c.activated_at) || '-12-31')::date
//                        ) = 0
//                    )
//            ";
//
//        $connection = Propel::getConnection();
//
//        $statement = $connection->prepare($query);
//        $statement->execute();
//        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
//            $contract = ContractPeer::retrieveByPK($row['id']);
//            if($contract)
//            {
//                $this->addEndOfFirstyearSettlementForContract($contract);
//            }
//        }
//
//    }

    public function checkContractChanges(Contract $contract)
    {
        $this->checkContractActivation($contract);
        if ($contract->getActivatedAt()) {
            $this->addEndOfFirstyearSettlementForContractIfNotExists($contract);
            $this->generateSettlementsForContract($contract);
            $this->updateContractSettlements($contract);
        }
    }

    public function checkContractActivation(Contract $contract)
    {
        $paymentsAmount = $contract->getPaymentsAmount();
        if ($paymentsAmount >= $contract->getAmount()) {
            $activatedAt = $contract->getActivatedAt();
            $lastPayment = $contract->getLastPayment();
            if ($activatedAt != $lastPayment->getDate()) {
                $contract->setActivatedAt($lastPayment->getDate());
                $translateService = ServiceContainer::getTranslateService();
                ServiceContainer::getMessageService()->addSuccess($translateService->__('Contract activated'));
                $contract->save();
            }
        }
    }

    public function updateContractSettlements(contract $contract)
    {
        foreach ($contract->getSettlements() as $settlement) {
            $settlement->setBalance($this->getBalanceForSettlement($settlement));
            $settlement->setInterest($this->getInterestForSettlement($settlement));
            $settlement->save();
            $settlement->reload();
        }
    }

    public function generateSettlementsForContract(Contract $contract)
    {
        $nextSettlementDate = $this->getNextSettlementDateForContract($contract);
        $today = new DateTime();
        $closedAt = new DateTime($contract->getClosedAt());
        if ((!$closedAt || $closedAt && $closedAt >= $nextSettlementDate) && ($nextSettlementDate < $today)) {
            $newSettlement = $this->addSettlementForContract($contract, SettlementPeer::IN_PERIOD, $nextSettlementDate);
            $this->generateSettlementsForContract($contract);
        }
    }

    public function addEndOfFirstyearSettlementForContractIfNotExists(Contract $contract)
    {
        $contractActivatedAt = $contract->getActivatedAt();
        if (!$contractActivatedAt) {
            throw new Exception('Contract is not activated');
        }

        $activatedAtDate = new DateTime($contractActivatedAt);
        $endOfFirstYearDate = new DateTime($activatedAtDate->format('Y-12-31'));

        $criteria = new Criteria();
        $criteria->add(SettlementPeer::DATE, $endOfFirstYearDate);
        $criteria->add(SettlementPeer::SETTLEMENT_TYPE, SettlementPeer::END_OF_FIRST_YEAR);
        $endOfFirstyearSettlement = $contract->getSettlements($criteria);
        if (!$endOfFirstyearSettlement) {
            $endOfFirstyearSettlement = $this->addSettlementForContract($contract, SettlementPeer::END_OF_FIRST_YEAR, $endOfFirstYearDate);
        }

        return $endOfFirstyearSettlement;
    }

    public function addClosingSettlementForContract(Contract $contract)
    {
        $contractClosedAt = $contract->getClosedAt();
        if (!$contractClosedAt) {
            throw new Exception('Contract is not closed');
        }

        $closingSettlementDate = new DateTime($contractClosedAt);
        $closingSettlementDate->modify('-1 day');

        return $this->addSettlementForContract($contract, SettlementPeer::CLOSING, $closingSettlementDate);
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
            throw new Exception('Contract was not acitvated');
        }
        $lastSettlement = $contract->getLastSettlement(SettlementPeer::IN_PERIOD);
        if ($lastSettlement) {
            $previosDate = $lastSettlement->getDate();
        } else {
            $previosDate = $activatedAt;
        }

        $nextSettlementDate = new DateTime($previosDate);
        $nextSettlementDate->modify(sprintf('+%s month', $contract->getPeriodInMonths()));
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
        $balance = $settlement->getBalance();
        if (!$balance) {
            $balance = $this->getBalanceForSettlement($settlement);
        }

        $dateFrom = $this->getPreviousDateForSettlement($settlement);
        $dateTo = new DateTime($settlement->getDate());

        $interest = $balance * $contract->getInterestRate() / 100 * $dateTo->diff($dateFrom)->days / 365;
        $criteria = new Criteria();
        $criteria->add(SettlementPeer::DATE, $settlement->getDate());
        $criteria->add(SettlementPeer::ID, $settlement->getId(), Criteria::NOT_EQUAL);
        $otherSettlements = $contract->getSettlements($criteria);

        foreach ($otherSettlements as $settlement) {
            $interest -= $settlement->getInterest();
        }
        return $interest;
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

    protected function getDaysCount(DateTime $dateFrom, DateTime $dateTo)
    {
        $diff = $dateTo->diff($dateFrom);
        return $diff->days;
    }
}
