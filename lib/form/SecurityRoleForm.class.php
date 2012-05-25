<?php

/**
 * SecurityRole form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class SecurityRoleForm extends BaseSecurityRoleForm
{

    public function configure()
    {
        $this->disableCSRFProtection();
        $this->getValidator('name')->setOption('required', true);

        $this->unsetField('playground_id');
    }

    public function  doSave($con = null)
    {
        if($this->getObject()->isNew())
        {
            $this->getObject()->setPlayground(ServiceContainer::getPlaygroundService()->getCurrentPlayground());
        }
        parent::doSave($con);
    }

}
