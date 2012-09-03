<?php

class securityActions extends sfActions
{

    protected function onRightsChange()
    {
        $this->getUser()->reauthenticate();
    }

    public function executeRights(sfWebRequest $request)
    {
        $criteria = new Criteria();
        $this->perms = SecurityPermPeer::doSelect($criteria);

        $this->roles = SecurityRolePeer::doSelect($criteria);

        $this->users = SecurityUserPeer::doSelect($criteria);

        $this->rolePerms = array();
        foreach (SecurityRolePermPeer::doSelectJoinAll($criteria) as $rolePerm) {
            $this->rolePerms[$rolePerm->getRoleId()][$rolePerm->getPermId()] = $rolePerm;
        }

        $this->userRoles = array();
        foreach (SecurityUserRolePeer::doSelectJoinAll($criteria) as $userRole) {
            $this->userRoles[$userRole->getUserId()][$userRole->getRoleId()] = $userRole;
        }

        $this->userPerms = array();
        foreach (SecurityUserPermPeer::doSelectJoinAll($criteria) as $userPerm) {
            $this->userPerms[$userPerm->getUserId()][$userPerm->getPermId()] = $userPerm;
        }

        $this->roleForm = new SecurityRoleForm();

        if ($request->isMethod('post')) {
            $this->roleForm->bind($request->getParameter($this->roleForm->getName()));
        }
    }

    public function executeDeleteRole(sfWebRequest $request)
    {
        $roleId = $request->getParameter('id');
        $role = SecurityRolePeer::doDelete($roleId);
        $this->onRightsChange();
        return $this->redirect('@rights');
    }

    public function executeCreateRole(sfWebRequest $request)
    {
        $this->roleForm = new SecurityRoleForm();

        $this->roleForm->bind($request->getParameter($this->roleForm->getName()));
        if ($this->roleForm->isValid()) {
            $this->roleForm->save();
            $notice = 'Role created successfully';
            ServiceContainer::getMessageService()->addSuccess($notice);
            $this->onRightsChange();
            return $this->redirect('@rights');
        } else {
            $error = 'Role creating failed';
            ServiceContainer::getMessageService()->addError($error);
        }

        return $this->forward('security', 'rights');
    }

    public function executeCreateUserPerm(sfWebRequest $request)
    {
        $userId = $request->getParameter('user_id');
        $permId = $request->getParameter('perm_id');

        $criteria = new Criteria();
        $criteria->add(SecurityUserPermPeer::USER_ID, $userId);
        $criteria->add(SecurityUserPermPeer::PERM_ID, $permId);

        if (!SecurityUserPermPeer::doSelectOne($criteria)) {
            $userPerm = new SecurityUserPerm();
            $userPerm->setUserId($userId);
            $userPerm->setPermId($permId);

            $userPerm->save();

            $notice = 'Perm added successfully';
            ServiceContainer::getMessageService()->addSuccess($notice);
        }

        $this->onRightsChange();
        return $this->redirect('@rights');
    }

    public function executeDeleteUserPerm(sfWebRequest $request)
    {
        $userId = $request->getParameter('user_id');
        $permId = $request->getParameter('perm_id');

        $criteria = new Criteria();
        $criteria->add(SecurityUserPermPeer::USER_ID, $userId);
        $criteria->add(SecurityUserPermPeer::PERM_ID, $permId);

        SecurityUserPermPeer::doDelete($criteria);

        $notice = 'Perm deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);
        $this->onRightsChange();
        return $this->redirect('@rights');
    }

    public function executeCreateUserRole(sfWebRequest $request)
    {
        $userId = $request->getParameter('user_id');
        $roleId = $request->getParameter('role_id');

        $criteria = new Criteria();
        $criteria->add(SecurityUserRolePeer::USER_ID, $userId);
        $criteria->add(SecurityUserRolePeer::ROLE_ID, $roleId);

        if (!SecurityUserRolePeer::doSelectOne($criteria)) {
            $userRole = new SecurityUserRole();
            $userRole->setUserId($userId);
            $userRole->setRoleId($roleId);

            $userRole->save();

            $notice = 'Role added successfully';
            ServiceContainer::getMessageService()->addSuccess($notice);
        }
        $this->onRightsChange();
        return $this->redirect('@rights');
    }

    public function executeDeleteUserRole(sfWebRequest $request)
    {
        $userId = $request->getParameter('user_id');
        $roleId = $request->getParameter('role_id');
        $role = SecurityRolePeer::retrieveByPK($roleId);
        $this->forward404Unless($role);

        $criteria = new Criteria();
        $criteria->add(SecurityUserRolePeer::USER_ID, $userId);
        $criteria->add(SecurityUserRolePeer::ROLE_ID, $roleId);

        SecurityUserRolePeer::doDelete($criteria);

        $notice = 'Role removed successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);

        $this->onRightsChange();
        return $this->redirect('@rights');
    }

    public function executeCreateRolePerm(sfWebRequest $request)
    {
        $roleId = $request->getParameter('role_id');
        $role = SecurityRolePeer::retrieveByPK($roleId);
        $this->forward404Unless($role);

        $permId = $request->getParameter('perm_id');

        $criteria = new Criteria();
        $criteria->add(SecurityRolePermPeer::ROLE_ID, $roleId);
        $criteria->add(SecurityRolePermPeer::PERM_ID, $permId);

        if (!SecurityRolePermPeer::doSelectOne($criteria)) {
            $rolePerm = new SecurityRolePerm();
            $rolePerm->setRoleId($roleId);
            $rolePerm->setPermId($permId);

            $rolePerm->save();

            $notice = 'Perm added successfully';
            ServiceContainer::getMessageService()->addSuccess($notice);
        }

        $this->onRightsChange();
        return $this->redirect('@rights');
    }

    public function executeDeleteRolePerm(sfWebRequest $request)
    {
        $roleId = $request->getParameter('role_id');
        $role = SecurityRolePeer::retrieveByPK($roleId);

        $permId = $request->getParameter('perm_id');

        $criteria = new Criteria();
        $criteria->add(SecurityRolePermPeer::ROLE_ID, $roleId);
        $criteria->add(SecurityRolePermPeer::PERM_ID, $permId);

        SecurityRolePermPeer::doDelete($criteria);

        $notice = 'Perm deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);

        $this->onRightsChange();
        return $this->redirect('@rights');
    }

    public function executeLogin(sfWebRequest $request)
    {
        $this->getResponse()->setStatusCode(401);
        if ($this->getUser()->isAuthenticated()) {
            return $this->redirect('@homepage');
        }
        $this->form = new LoginForm();
        if ($request->isMethod('post')) {
            $login = $request->getParameter('login');
            $this->form->bind($login);
            if ($this->form->isValid()) {
                if ($this->getUser()->login($login)) {
                    $referer = $request->getReferer();
                    $redirect = '@homepage';
                    if ($referer) {
                        $redirect = $referer;
                    }
                    return $this->redirect($redirect);
                } else {
                    if (SecurityUserPeer::retrieveByEmail($login['email'])) {
                        $error = 'Invalid password';
                    } else {
                        $error = 'Unknow user';
                    }
                    ServiceContainer::getMessageService()->addError($error);
                }
            } else {
                ServiceContainer::getMessageService()->addFormErrors($this->form);
            }
        }

        $this->registrationFrom = new registrationForm();
    }

    public function executeSecure(sfWebRequest $request)
    {

    }

    public function executeLogout($request)
    {
        $this->getUser()->logout();
        $this->redirect('@homepage');
    }

    public function executeNoScript()
    {
        die('no script');
    }

    public function executeShowLoginForm($request)
    {
        return $this->renderPartial('@login', array('form' => new LoginForm()));
    }

    public function executeAccount($request)
    {
        $this->user = $this->getUser()->getSecurityUser();
        $this->form = new AccountForm($this->user);

        if ($request->isMethod('post')) {
            $this->processAccountForm($request, $this->form);
        }
    }

    public function executeForgottenPassword($request)
    {
        $this->form = new ForgottenPasswordForm();

        if ($request->isMethod('post')) {
            $this->processForgottenPasswordForm($request, $this->form);
        }
    }

    public function executeError404(sfWebRequest $request)
    {

    }

    protected function processAccountForm(sfWebRequest $request, sfForm $form)
    {
        $translationService = ServiceContainer::getTranslateService();

        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $form->save();
            $success = $translationService->__('Account_update_success');
            ServiceContainer::getMessageService()->addSuccess($success);

            return $this->redirect('@account');
        }
    }

    protected function processForgottenPasswordForm(sfWebRequest $request, sfForm $form)
    {
        $translationService = ServiceContainer::getTranslateService();

        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $user = SecurityUserPeer::retrieveByEmail($this->form->getValue('email'));
            if (!$user) {
                $error = $translationService->__('forgotten_password_unknow_user');
                ServiceContainer::getMessageService()->addError($error);
            } elseif ($this->resendLogin($user)) {
                $notice = $translationService->__('forgotten_password_resend');
                ServiceContainer::getMessageService()->addSuccess($notice);
                return $this->redirect('@login');
            } else {
                $error = $translationService->__('forgotten_password_resend_failed');
                ServiceContainer::getMessageService()->addError($error);
            }
        }
    }

    protected function resendLogin(SecurityUser $user)
    {
        $culture = ServiceContainer::getTranslateService()->getCulture();
        $newPassword = substr(md5(time()), 0, 8);

        $replacements['%password%'] = $newPassword;
        $replacements['%url%'] = $this->getHomepageUrl();

        $forgottenPasswordMailSubject = sfConfig::get('app_forgotten_password_mail_subject_' . $culture);
        $forgottenPasswordMailSubject = str_replace(array_keys($replacements), $replacements, $forgottenPasswordMailSubject);

        $forgottenPasswordMailMessage = sfConfig::get('app_forgotten_password_mail_message_' . $culture);
        $forgottenPasswordMailMessage = str_replace(array_keys($replacements), $replacements, $forgottenPasswordMailMessage);

        $send = $this->getMailer()->composeAndSend(array(MAILER_FROM_ADDRESS => MAILER_FROM_NAME), $user->getEmail(), $forgottenPasswordMailSubject, $forgottenPasswordMailMessage);

        if ($send) {
            $user->setPassword(md5($newPassword));
            $user->save();
        }
        return $send;
    }

    protected function getHomepageUrl()
    {
        $url = $this->generateUrl('homepage', array(), true);

        return $url;
    }
}
