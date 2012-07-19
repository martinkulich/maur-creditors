<?php

/**
 * contract module helper.
 *
 * @package    rezervuj
 * @subpackage contract
 * @author     Your name here
 * @version    SVN: $Id: helper.php 12474 2008-10-31 10:41:27Z fabien $
 */
class contractGeneratorHelper extends BaseContractGeneratorHelper
{

    public function linkTo_saveAndPayOtherContract($object, $params)
  {
    return '<li class="sf_admin_action_save">
                <input type="hidden" id="save_and_pay_other_contract" name="save_and_pay_other_contract" value="0">
                <button type="submit" class="btn btn-primary" onClick="jQuery(\'#save_and_pay_other_contract\').val(1)">
                    <i class="icon-ok icon-white">
                    </i> '.' '.__($params['label'], array(), 'sf_admin').
                '</button>
            </li>';

  }

    public function linkToCopy($object, $params)
    {
        return '<li class="sf_admin_action_copy">' . link_to('<i class="icon-edit icon-white"></i> ' . __($params['label']), $this->getUrlForAction('copy'), $object, array('class' => 'btn btn-success modal_link')) . '</li>';
    }

    public function linkToClose($object, $params)
    {
        return '<li class="sf_admin_action_close">' . link_to('<i class="icon-remove icon-white"></i> ' . __($params['label']), $this->getUrlForAction('close'), $object, array('class' => 'btn btn-danger modal_link')) . '</li>';
        //treba doladit
        return '<li class="sf_admin_action_close">' . link_to('<i class="icon-remove icon-white"></i> ' . __($params['label']), $this->getUrlForAction('close'), $object, array('class' => 'btn btn-danger modal_link', 'confirm' =>  __('Are you sure?', array(), 'sf_admin'))) . '</li>';
    }
}
