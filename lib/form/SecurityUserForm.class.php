<?php

/**
 * SecurityUser form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class SecurityUserForm extends BaseSecurityUserForm
{

    public function configure()
    {

        $widgetSchema = $this->getWidgetSchema();
        $validatorSchema = $this->getValidatorSchema();

        $this->setValidator('email', new sfValidatorEmail(array('required' => true)));
        $this->getValidator('phone')->setOption('required', false);


        //post validace

        $widgetSchema['password'] = new sfWidgetFormInputPassword();
        $widgetSchema['password_again'] = new sfWidgetFormInputPassword(array('label' => 'Password (again)'));
        $widgetSchema->moveField('password', sfWidgetFormSchema::LAST);
        $widgetSchema->moveField('password_again', sfWidgetFormSchema::AFTER, 'password');

        $validatorSchema['password_again'] = new sfValidatorPass();
        $validatorSchema['password'] = new sfValidatorRegex(array('required' => false, 'min_length'=>8, 'pattern'=>'/^\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*$/'), array('invalid'=>'Password must contain at least one lower case letter, at least one upper case letter and at least one number'));

        $emailPostValidator = new sfValidatorPropelUnique(array('model' => 'securityUser', 'column' => 'email'));
        $passwordCompareValidator = new sfValidatorSchemaCompare('password_again', '==', 'password', array(), array('invalid' => 'The passwords must be equal'));
        $passwordCheckValidator = new sfValidatorCallback(array('callback' => array($this, 'paswordCheck')));



        $postValidator = new sfValidatorAnd(array(
                    $emailPostValidator,
                    $passwordCompareValidator,
                    $passwordCheckValidator,
                ));

        $validatorSchema->setPostValidator($postValidator);

        $currentUserId = sfContext::getInstance()->getUser()->getId();

        $fieldsToUnset = array(
            'security_user_perm_list',
            'security_user_role_list',
            'price_user_list',
            'is_super_admin',
            'schedule_user_list',
            'security_user_group_list',
        );

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }

    }

    public function paswordCheck(sfValidatorBase $validator, array $values)
    {
        if ($this->getObject()->isNew() && array_key_exists('password', $values) && empty($values['password'])) {
            $error = new sfValidatorError($validator, 'Password is required!');

            //// throw an error schema so the error appears at the field "password"
            throw new sfValidatorErrorSchema($validator, array('password' => $error));
        }
        return $values;
    }

    protected function doSave($con = null)
    {
        $object = $this->getObject();

        $newPassword = $this->getValue('password');

        if (empty($newPassword)) {
            $newPassword = $object->getPassword();
        } else {
            $newPassword = md5($newPassword);
        }


        parent::doSave($con);
        $object->setPassword($newPassword);
        $object->save();
    }

    public function saveSecurityUserGroupList($con = null)
    {
        if (!$this->isValid()) {
            throw $this->getErrorSchema();
        }

        if (!isset($this->widgetSchema['security_user_group_list'])) {

            $groups = ServiceContainer::getSecurityService()->getAvailableGroupsForUser($this->getObject());
            if(count($groups) == 1)
            {
                $group = reset($groups);
                $value = $group->getId();
            }
            else
            {
                return;
            }
        }
        else
        {
            $value = $this->getValue('security_user_group_list');
        }

        if (null === $con) {
            $con = $this->getConnection();
        }

        $criteria = new Criteria();
        $criteria->add(SecurityUserPeer::ID, $this->object->getPrimaryKey());
        $criteria->addJoin(SecurityUserPeer::ID, SecurityUserGroupPeer::USER_ID);
        $criteria->addJoin(SecurityUserGroupPeer::GROUP_ID, SecurityGroupPeer::ID);
        $criteria->add(SecurityGroupPeer::PLAYGROUND_ID, ServiceContainer::getPlaygroundService()->getCurrentPlayground()->getId());
        foreach (SecurityUserGroupPeer::doSelect($criteria, $con) as $securityUserGroup) {
            $securityUserGroup->delete($con);
        }


        if ($value) {
            $obj = new SecurityUserGroup();
            $obj->setUserId($this->object->getPrimaryKey());
            $obj->setGroupId($value);
            $obj->save();
        }
    }

}
