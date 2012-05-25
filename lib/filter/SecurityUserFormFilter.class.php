<?php

/**
 * SecurityUser filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class SecurityUserFormFilter extends BaseSecurityUserFormFilter
{

    public function configure()
    {
        $fieldsToUnset = array(
            'password',
            'is_super_admin',
            'security_user_group_list',
            'security_user_role_list',
        );

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }
    }

}
