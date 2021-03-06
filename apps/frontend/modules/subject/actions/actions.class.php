<?php

require_once dirname(__FILE__) . '/../lib/subjectGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/subjectGeneratorHelper.class.php';

/**
 * subject actions.
 *
 * @package    rezervuj
 * @subpackage subject
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class subjectActions extends autoSubjectActions
{

    public function executePaidDetail(sfWebRequest $request)
    {
        $this->subject = $this->getRoute()->getObject();
        $this->forward404Unless($this->subject);

        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(OutgoingPaymentPeer::DATE);
        $criteria->add(OutgoingPaymentPeer::CREDITOR_ID, $this->subject->getId());

        $filters = $request->getParameter('filter');

        if (is_array($filters) && array_key_exists('year', $filters)) {

            $monthFrom = array_key_exists('month', $filters) ? $filters['month'] : '01';
            $monthTo = array_key_exists('month', $filters) ? $filters['month'] : '12';
            $firstDay = new DateTime(sprintf('%s-%s-01', $filters['year'], $monthFrom));
            $lastDay = new DateTime(sprintf('%s-%s-01', $filters['year'], $monthTo));
            $lastDay->modify('last day of this month');

            $criterion1 = $criteria->getNewCriterion(OutgoingPaymentPeer::DATE, $firstDay, Criteria::GREATER_EQUAL);
            $criterion2 = $criteria->getNewCriterion(OutgoingPaymentPeer::DATE, $lastDay, Criteria::LESS_EQUAL);
            $criterion1->addAnd($criterion2);
            $criteria->addAnd($criterion1);
        }

        if (is_array($filters) && array_key_exists('date_to', $filters)) {

            $criteria->add(OutgoingPaymentPeer::DATE, $filters['date_to'], Criteria::LESS_EQUAL);
        }

        if(is_array($filters) && array_key_exists('currency_code', $filters))
        {
            $criteria->add(OutgoingPaymentPeer::CURRENCY_CODE, $filters['currency_code']);
        }

        $this->outgoingPayments = OutgoingPaymentPeer::doSelect($criteria);
    }

    public function executeGiftList(sfWebRequest $request)
    {
        $this->subject = $this->getRoute()->getObject();
        $this->forward404Unless($this->subject);
    }

    public function executeAddGift(sfWebRequest $request)
    {
        $this->subject = $this->getRoute()->getObject();
        $this->forward404Unless($this->subject);

        $this->gift = new Gift();
        $this->gift->setSubject($this->subject);
        $this->form = new CreditorGiftForm($this->gift);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->processAddGiftForm($request, $this->form);
        }
    }

    protected function processAddGiftForm(sfWebRequest $request, sfForm $form)
    {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $notice = $form->getObject()->isNew() ? 'The item was created successfully' : 'The item was updated successfully';

            $gift = $form->save();


            $redirect = '@report?report_type=birthday';

            ServiceContainer::getMessageService()->addSuccess($notice);

            return $this->redirect($redirect, 205);
        } else {
            ServiceContainer::getMessageService()->addFromErrors($form);
        }
    }


}
