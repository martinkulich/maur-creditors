<?php

class SslFilter extends sfFilter
{

    public function execute($filterChain)
    {
        if (sfConfig::has('app_ssl') && sfConfig::get('app_ssl') == true) {

            $context = $this->getContext();
            $request = $context->getRequest();

            $isSecure = $request->isSecure();

            if (!$isSecure) {
                return self::doRedirect($context);
            }
        }
        // Continue on with the chain, but it will only do that if
        // we didn't need to redirect.
        $filterChain->execute();
    }

    public static function doRedirect($context)
    {
        $request = $context->getRequest();
        $controller = $context->getController();

        // Determine which direction we want to go

        $from = 'http://';
        $to = 'https://';

        $redirect_to = str_replace($from, $to, $request->getUri());
        return $controller->redirect($redirect_to, 0, 301);
    }
}
