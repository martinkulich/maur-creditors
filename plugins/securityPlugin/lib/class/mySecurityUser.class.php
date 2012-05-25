<?php

class mySecurityUser extends sfBasicSecurityUser
{
    protected $securityUser = null;
    private $isSuperAdmin = null;
    protected $atributeNamespace = null;

    public function login($login, $withPassword = true)
    {
        $this->logout();

        $criteria = new Criteria();
        $criteria->add(SecurityUserPeer::EMAIL, $login['email']);

        if ($withPassword) {
            $criteria->add(SecurityUserPeer::PASSWORD, md5($login['password']));
        }


        $user = SecurityUserPeer::doSelectOne($criteria);


        if (isset($user)) {
            if (!$user->getActive()) {
                ServiceContainer::getMessageService()->addError('User is not active');
            } else {
                $this->authenticate($user);
            }
            return true;
        }

        return false;
    }

    public function relogin()
    {
        $securityUser = $this->getSecurityUser();
        $this->logout();
        $this->authenticate($securityUser);
    }

    public function authenticate(SecurityUser $securityUser)
    {
        $this->clearCredentials();


        $userRoleCriteria = new Criteria();

        foreach ($securityUser->getSecurityUserRolesJoinSecurityRole($userRoleCriteria) as $securityUserRole) {
            foreach ($securityUserRole->getSecurityRole()->getSecurityRolePermsJoinSecurityPerm() as $securityRolePerm) {
                $this->addCredential($securityRolePerm->getSecurityPerm()->getCode());
            }
        }


        $userPermCriteria = new Criteria();

        foreach ($securityUser->getSecurityUserPermsJoinSecurityPerm($userPermCriteria) as $securityUserPerm) {

            $this->addCredential($securityUserPerm->getSecurityPerm()->getCode());
        }

        $this->setAttribute('security_user_id', $securityUser->getId());
        $this->setAuthenticated(true);
    }

    public function getId()
    {
        return $this->getAttribute('security_user_id');
    }

    public function getSecurityUser()
    {
        if (!$this->securityUser) {
            $this->securityUser = SecurityUserPeer::retrievebyPk($this->getId());
        }
        return $this->securityUser;
    }

    public function logout()
    {
        // clear, logout..
        $this->getAttributeHolder()->clear();
        $this->clearCredentials();
        $this->setAuthenticated(false);
    }

    public function hasCredential($credentials, $useAnd = true)
    {
        if ($this->isSuperAdmin === null) {
            if ($securityUser = $this->getSecurityUser()) {
                $this->isSuperAdmin = $securityUser->getIsSuperAdmin();
            }
        }
        if ($this->isSuperAdmin) {
            $hasCredential = true;
        } else {
            $hasCredential = parent::hasCredential($credentials, $useAnd = true);
        }

        return $hasCredential;
    }

    public function hasCredentialToEditObject($className, $objectId, $childColumn='created_by_user_id', $parentColumn ='id')
    {
        $peerClassName = ucfirst($className) . 'Peer';
        $object = call_user_func(array($peerClassName, 'retrieveByPk'), $objectId);
        if (!$object) {
            throw new exception(sprintf('Object of class %s with id %s do not exists', $className, $objectId));
        }
        $childMethod = 'get' . sfInflector::camelize($childColumn);
        $parentMethod = 'get' . sfInflector::camelize($parentColumn);
        if ($object->$childMethod() == $this->$parentMethod()) {
            return true;
        }

        return false;
    }

    protected function getNamespace()
    {
        if (is_null($this->atributeNamespace)) {
            $this->atributeNamespace = 'maur';
        }

        return $this->atributeNamespace;
    }
}
