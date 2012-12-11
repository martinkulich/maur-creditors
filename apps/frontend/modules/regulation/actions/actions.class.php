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
        $this->sums = $this->getSums();
    }

    protected function getSums()
    {   
        $sumPager = $this->getPager();
        $criteria = $sumPager->getCriteria();
        $criteria->clearSelectColumns();
        $criteria->addJoin(RegulationPeer::CONTRACT_ID, ContractPeer::ID);
        $criteria->addSelectColumn(ContractPeer::CURRENCY_CODE);
        $criteria->addSelectColumn(sprintf("sum(%s) as regulation", RegulationPeer::REGULATION));
        $criteria->addSelectColumn(sprintf("sum(%s) as start_balance", RegulationPeer::START_BALANCE));
        $criteria->addSelectColumn(sprintf("sum(%s) as contract_balance", RegulationPeer::CONTRACT_BALANCE));
        //$criteria->addSelectColumn(sprintf("sum(%s) as end_balance", RegulationPeer::END_BALANCE));
        $criteria->addSelectColumn(sprintf("sum(%s) as paid", RegulationPeer::PAID));
        $criteria->addSelectColumn(sprintf("sum(%s) as paid_for_current_year", RegulationPeer::PAID_FOR_CURRENT_YEAR));
        $criteria->addSelectColumn(sprintf("sum(%s) as capitalized", RegulationPeer::CAPITALIZED));
        $criteria->addSelectColumn(sprintf("sum(%s) as unpaid", RegulationPeer::UNPAID));
        $criteria->addSelectColumn(sprintf("sum(%s) as unpaid_in_past", RegulationPeer::UNPAID_IN_PAST));
        $criteria->clearOrderByColumns();
        $criteria->addGroupByColumn(ContractPeer::CURRENCY_CODE);
        $sumPager->setCriteria($criteria);
        $sumPager->init();

        $statement = RegulationPeer::doSelectStmt($sumPager->getCriteria());
        $sums = array();
        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row)
        {
            $sums[$row['currency_code']] = $row;
        }
        ksort($sums);
        
        $filters = $this->getFilters();
        if(!array_key_exists('regulation_year', $filters) || (array_key_exists('regulation_year', $filters) && !$filters['regulation_year']))
        {
            foreach($sums as  $currencyCode => $row)
            {
                unset($sums[$currencyCode]['unpaid']);
                unset($sums[$currencyCode]['start_balance']);
                
            }
        }
        return $sums;
    }
}
