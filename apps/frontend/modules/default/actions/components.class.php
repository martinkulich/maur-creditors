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
        $this->mainLinks = array(
            'creditor',
            'contract',
            'payment',
            'settlement',
            'regulation',
        );
        $this->adminLinks = array(
            'security_user',
            'ip_address',
            'rights',
            'currency',
        );

        $user = $this->getUser();
        
        $this->reportLinks = array();
        if ($user->hasCredential('report.admin')) {
            $this->reportLinks[] = 'unpaid';
            $this->reportLinks[] = 'balance';
            $this->reportLinks[] = 'summary';
        }
        
        $pattern = '%s.admin';
        foreach ($this->adminLinks as $key => $link) {
            $credential = sprintf($pattern, $link);
            if (!$user->hasCredential($credential)) {
                unset($this->adminLinks[$key]);
            }
        }

        foreach ($this->mainLinks as $key => $link) {
            $credential = sprintf($pattern, $link);
            if (!$user->hasCredential($credential)) {
                unset($this->mainLinks[$key]);
            }
        }

        $this->activeLinkIsAdministrationLink = in_array($this->activeLink, $this->adminLinks) || $this->activeLink == 'rights';
        $this->activeLinkIsReportLink = in_array($this->activeLink, $this->reportLinks);
    }

    public function executeFlashes()
    {
        $this->messages = ServiceContainer::getMessageService()->getAllMessages();
        ;
    }

}
