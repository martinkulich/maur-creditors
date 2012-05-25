<?php

/**
 * Curt filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class CurtFormFilter extends BaseCurtFormFilter
{

    public function configure()
    {

        $this->unsetField('playground_id');
        $this->unsetField('schedule_curt_list'); 
        $this->getWidgetSchema()->setLabel('curt_sport_list', 'Sport');
    }

    public function doBuildCriteria(array $values)
    {
        $criteria = parent::doBuildCriteria($values);
        $criteria->add(CurtPeer::PLAYGROUND_ID, ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId());

        return $criteria;
    }

}
