<?php

/**
 * user module configuration.
 *
 * @package    rezervuj
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: configuration.php 12474 2008-10-31 10:41:27Z fabien $
 */
class userGeneratorConfiguration extends BaseUserGeneratorConfiguration
{

    public function getPagerMaxPerPage()
    {
        $request = sfContext::getInstance()->getRequest();
        $filters = sfContext::getInstance()->getUser()->getAttribute('user.filters', array(), 'admin_module');
        $perPage = isset($filters['per_page']) ? $filters['per_page'] : 20;
        $perPage = $request->getParameter('per_page',$perPage);
        return $perPage;
    }

}
