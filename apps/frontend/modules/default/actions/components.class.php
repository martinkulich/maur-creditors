<?php

class defaultComponents extends sfComponents
{

    public function executeFooter()
    {
    }

    public function executeMenu(sfWebRequest $request)
    {
        $actions = array('new', 'creat', 'edit', 'delete', 'schedule');
        $this->userIsAuthenticated = $this->getUser()->isAuthenticated();

//        $this->userIsAuthenticated = true;
        if ($this->userIsAuthenticated) {
            $this->username = $this->getUser()->getSecurityUser()->getFullName();
        }
//
        $moduleName = sfContext::getInstance()->getModuleName();

        switch ($moduleName) {
            case 'securityUser':
            case 'ipAddress':
                $activeLink = sfInflector::underscore($moduleName);
                break;
            case 'security':
                $activeLink = sfContext::getInstance()->getActionName();
                break;
            case 'report':
                $activeLink = $request->getParameter('report_type');
                break;
            default:
                $activeLink = $moduleName;
                break;
        }

        $this->activeLink = $activeLink;
        $this->route = sfContext::getInstance()->getRouting()->getCurrentRouteName();
        $this->adminLinks = array(
            'creditor',
            'contract',
            'payment',
            'settlement',
            'security_user',
            'regulation',
            'ip_address'
        );
        $pattern = '%s.admin';
        $user = $this->getUser();
        foreach ($this->adminLinks as $key => $adminLink) {
            $credential = sprintf($pattern, $adminLink);
            if (!$user->hasCredential($credential)) {
//                unset($this->adminLinks[$key]);
            }
        }
        $this->activeLinkIsAdministrationLink = in_array($this->activeLink, $this->adminLinks) || $this->activeLink == 'rights';

        $this->userHasCredentialForReports = $user->hasCredential('report.admin');
        $this->reportLinks = array(
            'reservations'
        );

        $this->activeLinkIsReportLink = in_array($this->activeLink, $this->reportLinks);
    }

    public function executeFlashes()
    {
        $this->messages = ServiceContainer::getMessageService()->getAllMessages();
        ;
    }
}
