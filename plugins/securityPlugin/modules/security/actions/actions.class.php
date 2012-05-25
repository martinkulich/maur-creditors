<?php

class securityActions extends sfActions
{

    public function executeRights(sfWebRequest $request)
    {
        $playgroundId = ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId();
        $criteria = new Criteria();
        if (!$this->getUser()->hasCredential('show_non_public_perms')) {
            $criteria->add(SecurityPermPeer::IS_PUBLIC, true);
        }
        $this->perms = SecurityPermPeer::doSelect($criteria);

        $criteria = new Criteria();

        $criteria->add(SecurityRolePeer::PLAYGROUND_ID, $playgroundId);
        $this->roles = SecurityRolePeer::doSelect($criteria);

        $criteria = new Criteria();
        $criteria->addJoin(PlaygroundUserPeer::USER_ID, SecurityUserPeer::ID);
        $criteria->add(PlaygroundUserPeer::PLAYGROUND_ID, $playgroundId);
        $this->users = SecurityUserPeer::doSelect($criteria);

        $criteria = new Criteria();
        $criteria->add(SecurityRolePeer::PLAYGROUND_ID, $playgroundId);
        $criteria->addJoin(SecurityRolePeer::ID, SecurityRolePermPeer::ROLE_ID);
        $this->rolePerms = array();
        foreach (SecurityRolePermPeer::doSelectJoinAll($criteria) as $rolePerm) {
            $this->rolePerms[$rolePerm->getRoleId()][$rolePerm->getPermId()] = $rolePerm;
        }

        $criteria = new Criteria();
        $criteria->addJoin(SecurityRolePeer::ID, SecurityUserRolePeer::ROLE_ID);
        $criteria->add(SecurityRolePeer::PLAYGROUND_ID, $playgroundId);
        $criteria->addJoin(PlaygroundUserPeer::USER_ID, SecurityUserRolePeer::USER_ID);
        $criteria->add(PlaygroundUserPeer::PLAYGROUND_ID, $playgroundId);
        $this->userRoles = array();
        foreach (SecurityUserRolePeer::doSelectJoinAll($criteria) as $userRole) {
            $this->userRoles[$userRole->getUserId()][$userRole->getRoleId()] = $userRole;
        }

        $criteria = new Criteria();
        $criteria->addJoin(PlaygroundUserPeer::USER_ID, SecurityUserPermPeer::USER_ID);
        $criteria->add(PlaygroundUserPeer::PLAYGROUND_ID, $playgroundId);
        $criteria->add(SecurityUserPermPeer::PLAYGROUND_ID, $playgroundId);
        $this->userPerms = array();
        foreach (SecurityUserPermPeer::doSelectJoinAll($criteria) as $userPerm) {
            $this->userPerms[$userPerm->getUserId()][$userPerm->getPermId()] = $userPerm;
        }

        $this->roleForm = new SecurityRoleForm();


        $this->playgroundUserForm = $this->getPlaygroundForm();

        if ($request->isMethod('post')) {
            $requestedRole = $request->getParameter($this->roleForm->getName());
            $requestedPlaygroundUser = $request->getParameter($this->playgroundUserForm->getName());
            if ($requestedRole) {
                $this->roleForm->bind($requestedRole);
            }

            if ($requestedPlaygroundUser) {
                $this->playgroundUserForm->bind($requestedPlaygroundUser);
            }
        }
    }

    public function executeDeleteRole(sfWebRequest $request)
    {
        $roleId = $request->getParameter('id');
        $role = SecurityRolePeer::retrieveByPK($roleId);
        $this->checkObjectEditCredential($role->getPlayground());
        $role->delete();
        $notice = 'Role deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);
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
            return $this->redirect('@rights');
        } else {
            $error = 'Role creating failed';
            ServiceContainer::getMessageService()->addError($error);
        }

        return $this->forward('security', 'rights');
    }

    public function executeDeletePlaygroundUser(sfWebRequest $request)
    {
        $userId = $request->getParameter('user_id');
        $user = SecurityUserPeer::retrieveByPK($userId);
        $this->forward404Unless($user);

        $playgroundId = ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId();

        $criteria = new Criteria();
        $criteria->add(PlaygroundUserPeer::USER_ID, $userId);
        $criteria->add(PlaygroundUserPeer::PLAYGROUND_ID, $playgroundId);
        $playgroundUser = PlaygroundUserPeer::doSelectOne($criteria);
        if ($playgroundUser) {
            $playgroundUser->delete();
        }

        //smazani jeho roly a prav na dane hriste
        $criteria = new Criteria();
        $criteria->addJoin(SecurityRolePeer::ID, SecurityUserRolePeer::ROLE_ID);
        $criteria->add(SecurityRolePeer::PLAYGROUND_ID, $playgroundId);
        $criteria->add(SecurityUserRolePeer::USER_ID, $userId);

        foreach (SecurityUserRolePeer::doSelect($criteria) as $userRole) {
            $userRole->delete();
        }


        $criteria = new Criteria();
        $criteria->add(SecurityUserPermPeer::PLAYGROUND_ID, $playgroundId);
        $criteria->add(SecurityUserPermPeer::USER_ID, $userId);

        foreach (SecurityUserPermPeer::doSelect($criteria) as $userPerm) {
            $userPerm->delete();
        }

        $notice = 'User deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);

        return $this->redirect('@rights');
    }

    public function executeCreatePlaygroundUser(sfWebRequest $request)
    {
        $this->playgroundUserForm = $this->getPlaygroundForm();

        $this->playgroundUserForm->bind($request->getParameter($this->playgroundUserForm->getName()));
        if ($this->playgroundUserForm->isValid()) {
            try {
                $this->playgroundUserForm->save();
            } catch (Exception $exc) {
                $error = $exc->getMessage();
                $error = 'Unable to add user';
                ServiceContainer::getMessageService()->addError($error);
            }
            $notice = 'Users added succesfully';
            ServiceContainer::getMessageService()->addSuccess($notice);
            return $this->redirect('@rights');
        } else {
            $error = 'User adding failed';
            ServiceContainer::getMessageService()->addError($error);
        }

        return $this->forward('security', 'rights');
    }

    public function executeCreateUserPerm(sfWebRequest $request)
    {
        $userId = $request->getParameter('user_id');
        $permId = $request->getParameter('perm_id');
        $playgroundId = ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId();

        $criteria = new Criteria();
        $criteria->add(SecurityUserPermPeer::USER_ID, $userId);
        $criteria->add(SecurityUserPermPeer::PERM_ID, $permId);
        $criteria->add(SecurityUserPermPeer::PLAYGROUND_ID, $playgroundId);

        if (!SecurityUserPermPeer::doSelectOne($criteria)) {
            $userPerm = new SecurityUserPerm();
            $userPerm->setUserId($userId);
            $userPerm->setPermId($permId);
            $userPerm->setPlaygroundId($playgroundId);

            $userPerm->save();

            $notice = 'Perm added successfully';
            ServiceContainer::getMessageService()->addSuccess($notice);
        }
        return $this->redirect('@rights');
    }

    public function executeDeleteUserPerm(sfWebRequest $request)
    {
        $userId = $request->getParameter('user_id');
        $permId = $request->getParameter('perm_id');
        $playgroundId = ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId();

        $criteria = new Criteria();
        $criteria->add(SecurityUserPermPeer::USER_ID, $userId);
        $criteria->add(SecurityUserPermPeer::PERM_ID, $permId);
        $criteria->add(SecurityUserPermPeer::PLAYGROUND_ID, $playgroundId);

        SecurityUserPermPeer::doDelete($criteria);

        $notice = 'Perm deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);

        return $this->redirect('@rights');
    }

    public function executeCreateUserRole(sfWebRequest $request)
    {
        $userId = $request->getParameter('user_id');
        $roleId = $request->getParameter('role_id');
        $role = SecurityRolePeer::retrieveByPK($roleId);
        $this->forward404Unless($role);
        $this->checkObjectEditCredential($role->getPlayground());


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
        return $this->redirect('@rights');
    }

    public function executeDeleteUserRole(sfWebRequest $request)
    {
        $userId = $request->getParameter('user_id');
        $roleId = $request->getParameter('role_id');
        $role = SecurityRolePeer::retrieveByPK($roleId);
        $this->forward404Unless($role);
        $this->checkObjectEditCredential($role->getPlayground());

        $criteria = new Criteria();
        $criteria->add(SecurityUserRolePeer::USER_ID, $userId);
        $criteria->add(SecurityUserRolePeer::ROLE_ID, $roleId);

        SecurityUserRolePeer::doDelete($criteria);

        $notice = 'Role removed successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);

        return $this->redirect('@rights');
    }

    public function executeCreateRolePerm(sfWebRequest $request)
    {
        $roleId = $request->getParameter('role_id');
        $role = SecurityRolePeer::retrieveByPK($roleId);
        $this->forward404Unless($role);
        $this->checkObjectEditCredential($role->getPlayground());

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
        return $this->redirect('@rights');
    }

    public function executeDeleteRolePerm(sfWebRequest $request)
    {
        $roleId = $request->getParameter('role_id');
        $role = SecurityRolePeer::retrieveByPK($roleId);
        $this->checkObjectEditCredential($role->getPlayground());

        $permId = $request->getParameter('perm_id');

        $criteria = new Criteria();
        $criteria->add(SecurityRolePermPeer::ROLE_ID, $roleId);
        $criteria->add(SecurityRolePermPeer::PERM_ID, $permId);

        SecurityRolePermPeer::doDelete($criteria);

        $notice = 'Perm deleted successfully';
        ServiceContainer::getMessageService()->addSuccess($notice);

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
                ServiceContainer::getMessageService()->addFromErrors($this->form);
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

    public function executeRegistration($request)
    {
        if ($this->getUser()->isAuthenticated()) {
            return $this->redirect('@homepage');
        }

        $this->form = new RegistrationForm();

        if ($request->isMethod('post')) {
            $this->processRegistrationForm($request, $this->form);
        }

        $this->loginForm = new LoginForm();
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

    protected function processRegistrationForm(sfWebRequest $request, sfForm $form)
    {
        $translationService = ServiceContainer::getTranslateService();

        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $form->save();
            $notice = $translationService->__('registration_successfull');
            ServiceContainer::getMessageService()->addSuccess($notice);

            $user = $form->getObject();
            if ($this->sendRegistrationMail($form)) {
                $notice = $translationService->__('registration_mail_send');
                ServiceContainer::getMessageService()->addSuccess($notice);

                $login['email'] = $form->getValue('email');
//                $this->getUser()->login($login, false);
                return $this->redirect('@login');
            } else {
                $error = $translationService->__('registration_mail_send_failed');
                ServiceContainer::getMessageService()->addError($error);
            }
        } else {
            ServiceContainer::getMessageService()->addFromErrors($form);
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

    protected function sendRegistrationMail(securityUserForm $form)
    {
        $culture = ServiceContainer::getTranslateService()->getCulture();

        $replacements['%password%'] = $form->getValue('password');
        $replacements['%url%'] = $this->getHomepageUrl();


        $registrationMailSubject = sfConfig::get('app_registration_mail_subject_' . $culture);
        $registrationMailSubject = str_replace(array_keys($replacements), $replacements, $registrationMailSubject);

        $registrationMailMessage = sfConfig::get('app_registration_mail_message_' . $culture);
        $registrationMailMessage = str_replace(array_keys($replacements), $replacements, $registrationMailMessage);

        $send = $this->getMailer()->composeAndSend(array(MAILER_FROM_ADDRESS => MAILER_FROM_NAME), $form->getValue('email'), $registrationMailSubject, $registrationMailMessage);

        return $send;
    }

    protected function getHomepageUrl($withPlayground = false)
    {
        $url = $this->generateUrl('homepage', array(), true);

        if (!$withPlayground) {
            $slug = ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getSlug();
            $url = str_replace($slug . '.', '', $url);
        }

        return $url;
    }

    protected function getPlaygroundForm()
    {
        $playgroundUser = new PlaygroundUser();
        $playgroundUser->setPlayground(ServiceContainer::getPlaygroundService()->getCurrentPlayground());
        return new PlaygroundUserForm($playgroundUser);
    }

    protected function checkObjectEditCredential(Playground $playground)
    {
        if ($playground->getId() != ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId()) {
            return $this->forward('security', 'secure');
        }
    }
}
