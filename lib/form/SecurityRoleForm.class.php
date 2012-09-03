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
    }
}
