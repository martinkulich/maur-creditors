<?php

/**
 * settlement module helper.
 *
 * @package    rezervuj
 * @subpackage settlement
 * @author     Your name here
 * @version    SVN: $Id: helper.php 12474 2008-10-31 10:41:27Z fabien $
 */
class settlementGeneratorHelper extends BaseSettlementGeneratorHelper
{

    public function linkToCapitalize($object, $params)
    {
        return '<li class="sf_admin_action_capitalize">' . link_to('<i class="icon-edit "></i> ' . __($params['label']), $this->getUrlForAction('capitalize'), $object, array('class' => 'btn')) . '</li>';
    }

    public function linkToPay($object, $params)
    {
        return '<li class="sf_admin_action_pay">' . link_to('<i class="icon-edit "></i> ' . __($params['label']), $this->getUrlForAction('pay'), $object, array('class' => 'btn')) . '</li>';
    }
}
