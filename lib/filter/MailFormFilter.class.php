<?php

/**
 * Mail filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class MailFormFilter extends BaseMailFormFilter
{

    public function configure()
    {
        $this->getWidgetSchema()->moveField('sender', sfWidgetFormSchema::FIRST);
        $fieldsToUnset = array(
            'created_at',
            'created_by_user_id',
            'playground_id',
        );

        foreach($fieldsToUnset as $field)
        {
            $this->unsetField($field);
        }

        $this->getWidget('send')->setLabel('Sended');
    }

    public function doBuildCriteria(array $values)
    {
        $criteria = parent::doBuildCriteria($values);
        $criteria->add(MailPeer::PLAYGROUND_ID, ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId());

        return $criteria;
    }

}
