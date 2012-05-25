<?php

/**
 * SecurityGroup form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class SecurityGroupForm extends BaseSecurityGroupForm
{

    public function configure()
    {
        $fieldsToUnset = array(
            'playground_id',
            'price_category_security_group_list',
            'security_user_group_list',
            'schedule_security_group_list',
        );

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }
    }

    public function doSave($con = null)
    {
        if ($this->getObject()->isNew()) {
            $this->getObject()->setPlayground(ServiceContainer::getPlaygroundService()->getCurrentPlayground());
        }

        parent::doSave($con);
    }

}
