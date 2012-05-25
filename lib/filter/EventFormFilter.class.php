<?php

/**
 * Event filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class EventFormFilter extends BaseEventFormFilter
{

    public function configure()
    {
        $fieldsToUnset = array(
            'event_type_code',
            'date',
            'perex',
            'descrip',
            'capacity',
            'players_count',
            'playground_id',
            'position_list',
            'allow_substitutions',
            'register_till',
        );


        $sportCriteria = new Criteria();
        $sportCriteria->addJoin(SportPeer::ID, PlaygroundSportPeer::SPORT_ID);
        $sportCriteria->add(PlaygroundSportPeer::PLAYGROUND_ID, ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId());
        $playgroundSportsCount = SportPeer::doCount($sportCriteria);
        if ($playgroundSportsCount == 1) {
            $fieldsToUnset[] = 'sport_id';
        }

        $currentPlayground = ServiceContainer::getPlaygroundService()->getCurrentPlayground();
        $eventGroupCriteria = new Criteria();
        $eventGroupCriteria->add(EventGroupPeer::PLAYGROUND_ID, $currentPlayground->getId());
        $eventGroupCount = EventGroupPeer::doCount($eventGroupCriteria);
        if ($eventGroupCount == 0 || !$currentPlayground->hasModule('event_group')) {
            $fieldsToUnset[] = 'event_group_id';
        } else {
            $this->getWidget('event_group_id')->setOption('criteria', $eventGroupCriteria);
            $this->getValidator('event_group_id')->setOption('criteria', $eventGroupCriteria);
        }

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }
    }

    public function doBuildCriteria(array $values)
    {
        $criteria = parent::doBuildCriteria($values);
        $criteria->add(EventPeer::PLAYGROUND_ID, ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId());


        return $criteria;
    }
}
