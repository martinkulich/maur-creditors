<?php

/**
 * SecurityUser form base class.
 *
 * @method SecurityUser getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseSecurityUserForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'email'                    => new sfWidgetFormInputText(),
      'password'                 => new sfWidgetFormInputText(),
      'firstname'                => new sfWidgetFormInputText(),
      'surname'                  => new sfWidgetFormInputText(),
      'phone'                    => new sfWidgetFormInputText(),
      'active'                   => new sfWidgetFormInputCheckbox(),
      'is_super_admin'           => new sfWidgetFormInputCheckbox(),
      'security_user_group_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'SecurityGroup')),
      'security_user_role_list'  => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'SecurityRole')),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'email'                    => new sfValidatorString(array('max_length' => 255)),
      'password'                 => new sfValidatorString(array('max_length' => 255)),
      'firstname'                => new sfValidatorString(array('max_length' => 255)),
      'surname'                  => new sfValidatorString(array('max_length' => 255)),
      'phone'                    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'active'                   => new sfValidatorBoolean(),
      'is_super_admin'           => new sfValidatorBoolean(),
      'security_user_group_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'SecurityGroup', 'required' => false)),
      'security_user_role_list'  => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'SecurityRole', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'SecurityUser', 'column' => array('email')))
    );

    $this->widgetSchema->setNameFormat('security_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SecurityUser';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['security_user_group_list']))
    {
      $values = array();
      foreach ($this->object->getSecurityUserGroups() as $obj)
      {
        $values[] = $obj->getGroupId();
      }

      $this->setDefault('security_user_group_list', $values);
    }

    if (isset($this->widgetSchema['security_user_role_list']))
    {
      $values = array();
      foreach ($this->object->getSecurityUserRoles() as $obj)
      {
        $values[] = $obj->getRoleId();
      }

      $this->setDefault('security_user_role_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveSecurityUserGroupList($con);
    $this->saveSecurityUserRoleList($con);
  }

  public function saveSecurityUserGroupList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['security_user_group_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(SecurityUserGroupPeer::USER_ID, $this->object->getPrimaryKey());
    SecurityUserGroupPeer::doDelete($c, $con);

    $values = $this->getValue('security_user_group_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new SecurityUserGroup();
        $obj->setUserId($this->object->getPrimaryKey());
        $obj->setGroupId($value);
        $obj->save();
      }
    }
  }

  public function saveSecurityUserRoleList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['security_user_role_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(SecurityUserRolePeer::USER_ID, $this->object->getPrimaryKey());
    SecurityUserRolePeer::doDelete($c, $con);

    $values = $this->getValue('security_user_role_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new SecurityUserRole();
        $obj->setUserId($this->object->getPrimaryKey());
        $obj->setRoleId($value);
        $obj->save();
      }
    }
  }

}
