<?php

/**
 * @author martinkulich <martin.kulich@imatic.cz>
 */

class IpAddressFilter extends sfFilter
{

    public function execute($filterChain)
    {
        $context = $this->getContext();

        if (!defined('DEBUG_MODE') || (defined('DEBUG_MODE') && DEBUG_MODE == false)) {
            if ($context->getUser()->isAuthenticated()) {
                $moduleName = $context->getModuleName();

                $route = $context->getRouting()->getCurrentRouteName();

                $uncheckedRoutes = array('login', 'logout', 'ip_address');

                if ($moduleName != 'ipAddress' && !in_array($route, $uncheckedRoutes)) {
                    $ipAddressValue = $_SERVER['REMOTE_ADDR'];
                    $criteria = new Criteria();
                    $criteria->add(IpAddressPeer::IP_ADDRESS, $ipAddressValue);
                    $ipAddressExists = IpAddressPeer::doCount($criteria) == 1;
                    if (!$ipAddressExists) {
                        $error = ServiceContainer::getTranslateService()->__('Invalid IP address');
                        ServiceContainer::getMessageService()->addError($error . ' ' . $ipAddressValue);
                        return self::doRedirect($context);
                    }
                }
            }
        }
        // Continue on with the chain, but it will only do that if
        // we didn't need to redirect.
        $filterChain->execute();
    }

    public static function doRedirect($context)
    {
        $controller = $context->getController();

        return $controller->redirect('@ip_address');
    }
}
