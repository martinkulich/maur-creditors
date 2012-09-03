<?php

class registrationForm extends SecurityUserForm
{

    public function configure()
    {
        parent::configure();

        $fieldsToUnset = array(
            'id',
            'active',
            'security_user_perm_list',
            'security_user_role_list',
            'price_user_list',
        );

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

        $this->disableCSRFProtection();
    }

}