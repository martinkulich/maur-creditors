<?php

/**
 * Schedule filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class ScheduleFormFilter extends BaseScheduleFormFilter
{

    public function configure()
    {
        $fieldsToUnset = array(
            'playground_id',
            'active_from',
            'active_to',
            'period',
            'time_from',
            'time_to',
            'schedule_user_list',
            'schedule_security_group_list',
        );
        $this->getWidget('is_public')->setLabel('Schedule is public');
        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

        $scheduleCurtCriteria = new Criteria();
        $scheduleCurtCriteria->add(CurtPeer::PLAYGROUND_ID, ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId());
        $scheduleCurtCriteria->addAscendingOrderByColumn(CurtPeer::NAME);
        $this->getWidget('schedule_curt_list')->setOption('criteria', $scheduleCurtCriteria);
        $this->getValidator('schedule_curt_list')->setOption('criteria', $scheduleCurtCriteria);
    }

    public function doBuildCriteria(array $values)
    {
        $criteria = parent::doBuildCriteria($values);
        $criteria->add(SchedulePeer::PLAYGROUND_ID, ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId());

        return $criteria;
    }

}
