<?php

require_once dirname(__FILE__) . '/../lib/regulationGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/regulationGeneratorHelper.class.php';

/**
 * regulation actions.
 *
 * @package    rezervuj
 * @subpackage regulation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class regulationActions extends autoRegulationActions
{

    public function executeIndex(sfWebRequest $request)
    {
        parent::executeIndex($request);
        $this->currency = ServiceContainer::getCurrencyService()->getDefaultCurrency();
        $this->sums = $this->getSums($this->currency);
        $filters = $this->getFilters();
        if(!array_key_exists('regulation_year', $filters) || (array_key_exists('regulation_year', $filters) && !$filters['regulation_year']['text']))
        {
            unset($this->sums['unpaid']);
        }
    }

    protected function getSums(Currency $currency)
    {   
        $sumPager = $this->getPager();
        $criteria = $sumPager->getCriteria();
        $criteria->clearSelectColumns();
        $criteria->addJoin(RegulationPeer::CONTRACT_ID, ContractPeer::ID);
        $criteria->addSelectColumn(sprintf("sum(amount_in_currency(%s, %s, '%s')) as regulation", RegulationPeer::REGULATION, ContractPeer::CURRENCY_CODE, $currency->getCode()));
        $criteria->addSelectColumn(sprintf("sum(amount_in_currency(%s, %s, '%s')) as start_balance", RegulationPeer::START_BALANCE, ContractPeer::CURRENCY_CODE, $currency->getCode()));
        $criteria->addSelectColumn(sprintf("sum(amount_in_currency(%s, %s, '%s')) as contract_balance", RegulationPeer::CONTRACT_BALANCE, ContractPeer::CURRENCY_CODE, $currency->getCode()));
        $criteria->addSelectColumn(sprintf("sum(amount_in_currency(%s, %s, '%s')) as end_balance", RegulationPeer::END_BALANCE, ContractPeer::CURRENCY_CODE, $currency->getCode()));
        $criteria->addSelectColumn(sprintf("sum(amount_in_currency(%s, %s, '%s')) as paid", RegulationPeer::PAID, ContractPeer::CURRENCY_CODE, $currency->getCode()));
        $criteria->addSelectColumn(sprintf("sum(amount_in_currency(%s, %s, '%s')) as paid_for_current_year", RegulationPeer::PAID_FOR_CURRENT_YEAR, ContractPeer::CURRENCY_CODE, $currency->getCode()));
        $criteria->addSelectColumn(sprintf("sum(amount_in_currency(%s, %s, '%s')) as capitalized", RegulationPeer::CAPITALIZED, ContractPeer::CURRENCY_CODE, $currency->getCode()));
        $criteria->addSelectColumn(sprintf("sum(amount_in_currency(%s, %s, '%s')) as unpaid", RegulationPeer::UNPAID, ContractPeer::CURRENCY_CODE, $currency->getCode()));
        $criteria->addSelectColumn(sprintf("sum(amount_in_currency(%s, %s, '%s')) as unpaid_in_past", RegulationPeer::UNPAID_IN_PAST, ContractPeer::CURRENCY_CODE, $currency->getCode()));
        $criteria->clearOrderByColumns();
        $sumPager->setCriteria($criteria);
        $sumPager->init();

        $statement = RegulationPeer::doSelectStmt($sumPager->getCriteria());
        return $statement->fetch(PDO::FETCH_ASSOC);
        return $sumPager;
    }
}
