<?php

/**
 * schedule actions.
 *
 * @package    rezervuj
 * @subpackage schedule
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reservationPluginActions extends sfActions
{

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        $playground = ServiceContainer::getPlaygroundService()->getCurrentPlayground();
        $sportSlug = $this->getUser()->getAttribute('sport_slug');
        $sport = SportPeer::retrieveBySlug($sportSlug);

        if (!$sport) {
            $this->sports = ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getSports();
            $sport = ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getSport();

            if (!array_key_exists($sport->getId(), $this->sports)) {
                $sport = reset($this->sports);
            }
        }
        $this->redirectToSport($sport->getSlug());
    }

    public function executeDetail(sfWebRequest $request)
    {
        $this->reservation = ReservationPeer::retrieveByPK($request->getParameter('id'));
        $this->forward404Unless($this->reservation);

        $this->user = $this->reservation->getSecurityUserRelatedByUserId();

        $this->defaultCurrency = ServiceContainer::getCurrencyService()->getDefaultCurrency()->getCode();
    }

    public function executeSport(sfWebRequest $request)
    {
        $dateObject = new DateTime($request->getParameter('date', $this->getUser()->getAttribute('date', date('Y-m-d'))));
        $this->date = $dateObject->format('Y-m-d');
        $this->getUser()->setAttribute('date', $this->date);

        $priceCategoryId = $request->getParameter('price_category_id', $this->getUser()->getAttribute('price_category_id'));
        $this->priceCategory = null;
        if ($priceCategoryId) {
            $this->priceCategory = PriceCategoryPeer::retrieveByPK($priceCategoryId);
            $this->forward404Unless($this->priceCategory);
            $this->getUser()->setAttribute('price_category_id', $this->priceCategory->getId());
        }

        $sportId = $request->getParameter('sport_id');
        if ($sportId) {
            $this->sport = SportPeer::retrieveByPK($sportId);
            $this->forward404Unless($this->sport);

            return $this->redirectToSport($this->sport->getSlug(), new DateTime($this->date), $this->priceCategory, $dayCount);
        }

        $currentPlayground = ServiceContainer::getPlaygroundService()->getCurrentPlayground();


        $this->dayCount = $request->getParameter('day_count', $this->getUser()->getAttribute('day_count', 1));
        $this->getUser()->setAttribute('day_count', $this->dayCount);

        $this->dates = array();
        $newDateObject = clone $dateObject;
        for ($i = 1; $i <= $this->dayCount; $i++) {
            $this->dates[] = $newDateObject->format('Y-m-d');
            $newDateObject->modify('+1 day');
        }
        $i18n = sfContext::getInstance()->getI18N();
        $this->dayCounts = array(
            1 => '1 ' . $i18n->__('day'),
            2 => '2 ' . $i18n->__('days'),
            3 => '3 ' . $i18n->__('days'),
            7 => '1 ' . $i18n->__('week'),
        );


        $this->sportSlug = $request->getParameter('sport_slug', $this->getUser()->getAttribute('sport_slug'));
        if (!$this->sportSlug) {
            $this->sportSlug = $currentPlayground->getSport()->getSlug();
        }

        $this->sport = SportPeer::retrieveBySlug($this->sportSlug);
        $this->forward404Unless($this->sport);
        $this->getUser()->setAttribute('sport_slug', $this->sportSlug);

        $this->sportId = $this->sport->getId();

        $this->sports = $currentPlayground->getSports();

        $this->showSportName = count($currentPlayground->getSports()) > 1;


        if (count($this->sports) == 0) {
            throw new Exception('Playground must have associated at least one sport');
        }
        $this->showSports = count($this->sports) > 1;


        $this->userPriceCategories = ServiceContainer::getPriceService()->getPriceCategoriesForUser();
        if (count($this->userPriceCategories) == 0) {
            $error = sfContext::getInstance()->getI18N()->__('Playground must have associated at least one price category');
            ServiceContainer::getMessageService()->addError($error);
            return $this->redirect('@price_category');
        }
        $this->showPriceCategories = count($this->userPriceCategories) > 1;



        $this->priceCategoryId = $request->getParameter('price_category_id');
        if (!$this->priceCategoryId) {
            $securityUser = $this->getUser()->getSecurityUser();
            if ($securityUser) {
                $criteria = new Criteria();
                $criteria->add(SecurityUserGroupPeer::USER_ID, $securityUser->getId());
                $criteria->addJoin(SecurityUserGroupPeer::GROUP_ID, SecurityGroupPeer::ID);
                $criteria->addJoin(SecurityGroupPeer::ID, PriceCategorySecurityGroupPeer::GROUP_ID);
                $criteria->addJoin(PriceCategorySecurityGroupPeer::PRICE_CATEGORY_ID, PriceCategoryPeer::ID);
                $criteria->add(PriceCategoryPeer::PLAYGROUND_ID, ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId());
                $userPriceCategory = PriceCategoryPeer::doSelectOne($criteria);
                if ($userPriceCategory) {
                    $this->priceCategoryId = $userPriceCategory->getId();
                }
            }

            if (!$this->priceCategoryId) {
                $firstPriceCategory = reset($this->userPriceCategories);
                $this->priceCategoryId = $firstPriceCategory->getId();
            }
        }

        $this->priceCategory = PriceCategoryPeer::retrieveByPK($this->priceCategoryId);
        $this->forward404Unless($this->priceCategory);
        $this->forward404Unless($this->priceCategory->getPlaygroundId() == $currentPlayground->getId());

        $scheduleService = ServiceContainer::getScheduleService();

        if (!$this->getUser()->isAuthenticated()) {
            $i18n = sfContext::getInstance()->getI18N();
            $link = "<a href='" . $this->generateUrl('login') . "'>" . $i18n->__('login') . "</a>";

            ServiceContainer::getMessageService()->addWarning(sprintf('* ' . $i18n->__('For reservation you must be %s'), $link));
        }
    }

    public function executeNew(sfWebRequest $request)
    {
        $this->createReservationFromRequest($request);
        $this->form = $this->getReservationForm($this->reservation);

//        return $this->redirectToReservationEdit($reservation);
    }

    public function executeCreate(sfWebRequest $request)
    {
        $this->executeNew($request);
        $this->processReservationForm($request, $this->form);
        $this->form->getObject()->setId(null);
        $this->SetTemplate('new');
    }

    public function executeEdit(sfWebRequest $request)
    {
        $this->reservation = reservationPeer::retrieveByPK($request->getParameter('id'));
        $this->forward404Unless($this->reservation);

        $this->sport = $this->reservation->getSport();
        $this->date = $this->reservation->getDate();
        $this->priceCategory = $this->reservation->getPriceCategory();
        $this->form = $this->getReservationForm($this->reservation);
    }

    public function executeUpdate(sfWebRequest $request)
    {
        $this->executeEdit($request);
        $this->processReservationForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request, $deleteAll = false)
    {
        $reservation = reservationPeer::retrieveByPK($request->getParameter('id'));
        $this->forward404Unless($reservation);

        if (!$this->getUser()->hasCredential('reservation.admin') && !$this->getUser()->hasCredentialToEditObject('reservation', $reservation->getId())) {
            //melo by byt presmerovani na secure stranku
            return $this->forward404();
        }


        try {
            ServiceContainer::getReservationService()->deleteReservation($reservation, $deleteAll);
        } catch (Exception $exc) {
            $error = $exc->getMessage();
            ServiceContainer::getMessageService()->addError($error);
            return $this->redirectToReservationEdit($reservation);
        }
        $notice = 'Reservation deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);
        return $this->redirectToSport($reservation->getSport()->getSlug(), new DateTime($reservation->getDate()), $reservation->getPriceCategory());
    }

    protected function processReservationForm(sfWebRequest $request, sfForm $form, $redirect = true)
    {
        $form->bind($request->getParameter($form->getName()));
        $error = false;
        if ($form->isValid()) {
            $con = Propel::getConnection();
            $con->beginTransaction();

            try {
                $reservation = $form->save($con);
                if (!$reservation->isNew()) {
                    $notice = 'Reservation saved successfully';
                } else {
                    $notice = 'Reservation created successfully';
                }
                ServiceContainer::getMessageService()->addSuccess($notice);
                $con->commit();
            } catch (Exception $exc) {
                $con->rollBack();
                $error = true;
//                ServiceContainer::getMessageService()->addError($exc->getMessage());
            }
            if (!$error && $redirect) {
                return $this->redirect('@reservation', 205);
            }
        } else {
            $messageService = ServiceContainer::getMessageService();
            $messageService->addFromErrors($form);
//            foreach($form->getErrorSchema()->getErrors() as $key=>$error)
//            {
//                $messageService->addError($key. ': '.$error->getMessage());
//            }
        }
    }

    public function executeDeleteAll(sfWebRequest $request)
    {
        return $this->executeDelete($request, true);
    }

    public function executeCancel(sfWebRequest $request)
    {
        $reservation = reservationPeer::retrieveByPK($request->getParameter('id'));
        $this->forward404Unless($reservation);

        if (!$reservation->getCompletedAt()) {
            ServiceContainer::getReservationService()->deleteReservation($reservation);
        }

        return $this->redirectToSport($reservation->getSport()->getSlug(), new DateTime($reservation->getDate()));
    }

    public function executeAutocompleteUser(sfWebRequest $request)
    {
        $fullNamesAndEmails = SecurityUserPeer::retrieveByDetails($request->getParameter('q'));
        return $this->renderText(json_encode($fullNamesAndEmails));
    }

    /**
     * @param sfWebRequest $request
     * @return Reservation
     */
    protected function createReservationFromRequest(sfWebRequest $request)
    {
        $priceCategoryId = $request->getParameter('price_category_id');
        $this->priceCategory = PriceCategoryPeer::retrieveByPK($priceCategoryId);
        $this->forward404Unless($this->priceCategory);
        $timeZoneId = $request->getParameter('time_zone_id');
        $this->timeZone = TimeZonePeer::retrieveByPK($timeZoneId);
        $this->forward404unless($this->timeZone);

        $this->sportSlug = $request->getParameter('sport_slug');
        $this->sport = SportPeer::retrieveBySlug($this->sportSlug);
        $this->forward404Unless($this->sport);
        $user = $this->getUser()->getSecurityUser();

        $this->date = $request->getParameter('date');

        $curtId = $request->getParameter('curt_id');
        $this->curt = CurtPeer::retrieveByPK($curtId);
        $this->forward404unless($this->curt);

        $reservationService = ServiceContainer::getReservationService();
        $this->reservation = $reservationService->createReservation($user, new DateTime($this->date), $this->sport, $this->priceCategory, $this->timeZone, $this->curt);

        return $this->reservation;
    }

    protected function redirectToSchedule($sportSlug = null, DateTime $date = null)
    {
        exit('obsolete');
        if (is_null($date)) {
            $date = new DateTime($this->getRequest()->getParameter('date'));
        }
        if (is_null($sportSlug)) {
            $sportSlug = $this->getRequest()->getParameter('sport_slug');
        }

        return $this->redirect('@reservation_schedule?sport_slug=' . $sportSlug . '&date=' . $date->format('Y-m-d'));
    }

    protected function redirectToSport($sportSlug = null, DateTime $date = null, PriceCategory $priceCategory = null, $dayCount = null, $status = 200)
    {
        $user = $this->getUser();
        $request = $this->getRequest();

        if (is_null($date)) {
            $date = new DateTime($request->getParameter('date', $user->getAttribute('date')));
        }
        if (is_null($sportSlug)) {
            $sportSlug = $request->getParameter('sport_slug', $user->getAttribute('sport_slug'));
        }

        $uri = '@reservation_sport?sport_slug=' . $sportSlug . '&date=' . $date->format('Y-m-d');

        $priceCategoryId = $request->getParameter('price_category_id', $user->getAttribute('price_category_id'));
        if (!is_null($priceCategory)) {
            $priceCategoryId = $priceCategory->getId();
        } else {
            $priceCategoryId = $request->getParameter('price_category_id', $user->getAttribute('price_category_id'));
        }

        if (!is_null($priceCategoryId)) {
            $uri .= '&price_category_id=' . $priceCategoryId;
        }

        if(is_null($dayCount))
        {
            $dayCount = $request->getParameter('day_count', $user->getAttribute('day_count'));
        }

        if (!is_null($dayCount)) {
            $uri .= '&day_count=' . $dayCount;
        }
        return $this->redirect($uri, $status);
    }

    protected function redirectToReservationEdit(Reservation $reservation)
    {
        exit('obsolete');
        return $this->redirect('@reservation_edit?id=' . $reservation->getId());
    }

    protected function getReservationForm(Reservation $reservation = null)
    {
        if ($this->getUser()->hasCredential('reservation.admin')) {
            $reservationForm = new ReservationForm($reservation);
        } else {
            $reservationForm = new ReservationConfirmForm($reservation);
        }
        return $reservationForm;
    }

    protected function getDateWidget()
    {
        return new myJQueryDateWidget(array('type' => 'text', 'date_format' => 'dd.mm.yy'), array('onchange' => "submit()", 'size' => 7, 'class' => 'datepicker'));
    }

    protected function getPriceCategoryWidget()
    {
        return new sfWidgetFormChoice(array('choices' => ServiceContainer::getPriceService()->getPriceCategoriesForUser()), array('onChange' => 'submit()'));
    }
}
