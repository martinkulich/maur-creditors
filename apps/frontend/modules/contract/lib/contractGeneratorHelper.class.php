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

    public function linkToSaveOrReuse()
    {
        return
            '<li class="sf_admin_action_close contract_close_button_wrapper">
                <button class="btn btn-primary" id="contract_close_button">
                    <i class="icon-ok icon-white">
                    </i> '.' '.__('Save').
                '</button>
            </li>
             <li class="sf_admin_action_save contract_save_button_wrapper">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-ok icon-white">
                    </i> '.' '.__('Save',array(), 'sf_admin').
                '</button>
            </li>
            <li class="sf_admin_action_save_and_reuse  contract_save_button_wrapper">
                <input type="hidden" id="save_and_reuse" name="save_and_reuse" value="0">
                <button type="submit" class="btn btn-danger" onClick="jQuery(\'#save_and_reuse\').val(1)">
                    <i class="icon-ok icon-white">
                    </i> ' . ' ' . __('Save and reuse') .
                '</button>
            </li>
            <script type="text/javascript">
                $(document).ready(function(){
                    $(\'.contract_save_button_wrapper\').hide();

                    $(\'#contract_close_button\').click(function(){
                        $(\'.contract_save_button_wrapper\').show();
                        $(\'.contract_close_button_wrapper\').hide();
                        return false;
                    })
                })
            </script>
            ';
    }

    public function linkToSaveAndReuse($object, $params)
    {
        return '<li class="sf_admin_action_save_and_reuse">
                <input type="hidden" id="save_and_reuse" name="save_and_reuse" value="0">
                <button type="submit" class="btn btn-primary" onClick="jQuery(\'#save_and_reuse\').val(1)">
                    <i class="icon-ok icon-white">
                    </i> ' . ' ' . __($params['label']) .
            '</button>
            </li>';
    }

    public function linkToCopy($object, $params)
    {
        return '<li class="sf_admin_action_copy">' . link_to('<i class="icon-edit icon-white"></i> ' . __($params['label']), $this->getUrlForAction('copy'), $object, array('class' => 'btn btn-success modal_link')) . '</li>';
    }

    public function linkToDownload($object, $params)
    {
        return '<li class="sf_admin_action_download">' . link_to('<i class="icon-download icon-white"></i> ' . __($params['label']), $this->getUrlForAction('download'), $object, array('class' => 'btn btn-primary')) . '</li>';
    }

    public function linkToClose($object, $params)
    {
        return '<li class="sf_admin_action_close">' . link_to('<i class="icon-remove icon-white"></i> ' . __($params['label']), '@settlement_close?contract_id='.$object->getId(), array('class' => 'btn btn-danger modal_link')) . '</li>';

    }
}
