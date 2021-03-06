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
            case 'outgoingPayment':
            case 'bankAccount':
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
            'subject',
            'contract_type',
            'contract',
            'payment',
            'outgoing_payment',
            'allocation',
            'settlement',
            'regulation',
        );
        $this->adminLinks = array(
            'security_user',
            'ip_address',
            'rights',
            'currency',
            'bank_account',
        );

        $user = $this->getUser();

        $this->reportLinks = array();
        if ($user->hasCredential('report.admin')) {
            $this->reportLinks[] = 'creditor_regulation';
            $this->reportLinks[] = 'debtor_regulation';
            $this->reportLinks[] = 'creditor_regulation_monthly';
            $this->reportLinks[] = 'debtor_regulation_monthly';
            $this->reportLinks[] = 'creditor_balance';
            $this->reportLinks[] = 'debtor_balance';
            $this->reportLinks[] = 'birthday';
            $this->reportLinks[] = 'cash_flow';
            $this->reportLinks[] = 'creditors';
            $this->reportLinks[] = 'debtors';
            $this->reportLinks[] = 'creditor_revenue';
            $this->reportLinks[] = 'debtor_cost';
            $this->reportLinks[] = 'creditor_confirmation';
            $this->reportLinks[] = 'debtor_confirmation';
            $this->reportLinks[] = 'creditor_unpaid';
            $this->reportLinks[] = 'debtor_unpaid';
            $this->reportLinks[] = 'to_pay';
            $this->reportLinks[] = 'to_receive';
            $this->reportLinks[] = 'payment';

            $pattern = 'report-%s';
            foreach ($this->reportLinks as $key => $link) {
                $credential = str_replace("_", "-", sprintf($pattern, $link));
                if (!$user->hasCredential($credential)) {
                    unset($this->reportLinks[$key]);
                }
            }
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
