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
            'price_category_security_group_list',
            'security_user_group_list',
            'schedule_security_group_list',
        );

        foreach($fieldsToUnset as $field)
        {
            $this->unsetField($field);
        }
    }
}
