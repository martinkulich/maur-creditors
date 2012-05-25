<?php

class SecurityService
{

    public function userHasCredentialToEditReservation(DateTime $date, Sport $sport)
    {
//        treba dodelat logiku
        return true;
    }

    public function getAvailableGroupsForUser(SecurityUser $user)
    {
        $groupCriteria = new Criteria();
        if (!sfContext::getInstance()->getUser()->hasCredential('user.admin')) {
            $criterion1 = $groupCriteria->getNewCriterion(SecurityGroupPeer::IS_PUBLIC, true);

            $grouIds = array();
            foreach ($user->getSecurityUserGroupsJoinSecurityGroup($groupCriteria) as $securityUserGroup) {
                $grouIds[] = $securityUserGroup->getGroupId();
            }
//        die(var_dump($grouIds));
            if (count($grouIds) > 0) {
                $criterion2 = $groupCriteria->getNewCriterion(SecurityGroupPeer::ID, $grouIds, Criteria::IN);
                $criterion1->addOr($criterion2);
            }
            $groupCriteria->addAnd($criterion1);
        }

        $groups = array();
        foreach (SecurityGroupPeer::doSelect($groupCriteria) as $group) {
            $groups[$group->getId()] = $group;
        }


        return $groups;
    }
}
