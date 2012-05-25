<?php

/**
 * SecurityGroup filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class SecurityGroupFormFilter extends BaseSecurityGroupFormFilter
{

    public function configure()
    {
        $fieldsToUnset = array(
            'playground_id',
            'price_category_security_group_list',
            'security_user_group_list',
            'schedule_security_group_list',
        );

        foreach($fieldsToUnset as $field)
        {
            $this->unsetField($field);
        }
    }

    public function doBuildCriteria(array $values)
    {
        $criteria = parent::doBuildCriteria($values);
        $criteria->add(SecurityGroupPeer::PLAYGROUND_ID, ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId());

        return $criteria;
    }

}
