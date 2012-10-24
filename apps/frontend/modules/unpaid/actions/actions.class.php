<?php

require_once dirname(__FILE__) . '/../lib/unpaidGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/unpaidGeneratorHelper.class.php';

/**
 * unpaid actions.
 *
 * @package    rezervuj
 * @subpackage unpaid
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class unpaidActions extends autoUnpaidActions
{

        public function executeIndex(sfWebRequest $request)
    {
        parent::executeIndex($request);
        $this->currency = ServiceContainer::getCurrencyService()->getDefaultCurrency();
        $this->sums = $this->getSums($this->currency);
    }

    protected function getSums(Currency $currency)
    {
        $sumPager = $this->getPager();
        $criteria = $sumPager->getCriteria();
        $criteria->clearSelectColumns();
        $criteria->addJoin(UnpaidPeer::CONTRACT_ID, ContractPeer::ID);
        $criteria->addSelectColumn(sprintf("sum(amount_in_currency(%s, %s, '%s')) as unpaid", UnpaidPeer::CONTRACT_UNPAID, ContractPeer::CURRENCY_CODE, $currency->getCode()));
        
        $criteria->clearOrderByColumns();
        $sumPager->setCriteria($criteria);
        $sumPager->init();

        $statement = UnpaidPeer::doSelectStmt($sumPager->getCriteria());
        return $statement->fetch(PDO::FETCH_ASSOC);
        return $sumPager;
    }
}
