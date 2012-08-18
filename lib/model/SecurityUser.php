<?php

require_once 'lib/model/om/BaseSecurityUser.php';

/**
 * Skeleton subclass for representing a row from the 'security_user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class SecurityUser extends BaseSecurityUser
{

    public function getFullName()
    {
        return $this->getSurname() . ' ' . $this->getFirstname();
    }

    public function getFullNameAndEmail()
    {
        return sprintf('%s (%s)', $this->getFullName(), $this->getEmail());
    }

    public function __toString()
    {
        return $this->getFullName();
    }

    public function getdetails()
    {
        $return = $this->getFullName();
        $contact = $this->getEmail();
        if ($phone = $this->getPhone()) {
            $contact .= ', ' . $phone;
        }

        return sprintf('%s (%s)', $return, $contact);
    }

    public function getPerms()
    {
        $perms = array();
        foreach ($this->getSecurityUserPermsJoinSecurityPerm() as $userPerm) {
            $perms[$userPerm->getPermId()] = $userPerm->getSecurityPerm();
        }
        return $perms;
    }

    public function getRoles()
    {
        $roles = array();
        foreach ($this->getSecurityUserRolesJoinSecurityRole() as $userRole) {
            $roles[$userRole->getRoleId()] = $userRole->getSecurityRole();
        }
        return $roles;
    }
}

// SecurityUser
