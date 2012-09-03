<?php

/**
 * Mail filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class MailFormFilter extends BaseMailFormFilter
{

    public function configure()
    {
        $this->getWidgetSchema()->moveField('sender', sfWidgetFormSchema::FIRST);
        $fieldsToUnset = array(
            'created_at',
            'created_by_user_id',
        );

        foreach($fieldsToUnset as $field)
        {
            $this->unsetField($field);
        }

        $this->getWidget('send')->setLabel('Sended');
    }

}
