<?php

/**
 * SecurityRole form base class.
 *
 * @method SecurityRole getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseSecurityRoleForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'name'                    => new sfWidgetFormInputText(),
      'security_role_perm_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'SecurityPerm')),
      'security_user_role_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'SecurityUser')),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'name'                    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'security_role_perm_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'SecurityPerm', 'required' => false)),
      'security_user_role_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'SecurityUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('security_role[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SecurityRole';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['security_role_perm_list']))
    {
      $values = array();
      foreach ($this->object->getSecurityRolePerms() as $obj)
      {
        $values[] = $obj->getPermId();
      }

      $this->setDefault('security_role_perm_list', $values);
    }

    if (isset($this->widgetSchema['security_user_role_list']))
    {
      $values = array();
      foreach ($this->object->getSecurityUserRoles() as $obj)
      {
        $values[] = $obj->getUserId();
      }

      $this->setDefault('security_user_role_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveSecurityRolePermList($con);
    $this->saveSecurityUserRoleList($con);
  }

  public function saveSecurityRolePermList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['security_role_perm_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(SecurityRolePermPeer::ROLE_ID, $this->object->getPrimaryKey());
    SecurityRolePermPeer::doDelete($c, $con);

    $values = $this->getValue('security_role_perm_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new SecurityRolePerm();
        $obj->setRoleId($this->object->getPrimaryKey());
        $obj->setPermId($value);
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
    $c->add(SecurityUserRolePeer::ROLE_ID, $this->object->getPrimaryKey());
    SecurityUserRolePeer::doDelete($c, $con);

    $values = $this->getValue('security_user_role_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new SecurityUserRole();
        $obj->setRoleId($this->object->getPrimaryKey());
        $obj->setUserId($value);
        $obj->save();
      }
    }
  }

}
