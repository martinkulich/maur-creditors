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
        $this->sums = $this->getSums();
    }

    protected function getSums()
    {
        $sumPager = $this->getPager();
        $criteria = $sumPager->getCriteria();
        $criteria->clearSelectColumns();
        $criteria->addSelectColumn(sprintf('sum(%s) as unpaid', UnpaidPeer::CONTRACT_UNPAID));
        $criteria->clearOrderByColumns();
        $sumPager->setCriteria($criteria);
        $sumPager->init();

        $statement = UnpaidPeer::doSelectStmt($sumPager->getCriteria());
        return $statement->fetch(PDO::FETCH_ASSOC);
        return $sumPager;
    }
}
