<?php

/**
 * SecurityPerm form base class.
 *
 * @method SecurityPerm getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseSecurityPermForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'code'                    => new sfWidgetFormInputText(),
      'name'                    => new sfWidgetFormInputText(),
      'is_public'               => new sfWidgetFormInputCheckbox(),
      'order_no'                => new sfWidgetFormInputText(),
      'security_role_perm_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'SecurityRole')),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'code'                    => new sfValidatorString(array('max_length' => 255)),
      'name'                    => new sfValidatorString(array('max_length' => 255)),
      'is_public'               => new sfValidatorBoolean(),
      'order_no'                => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'security_role_perm_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'SecurityRole', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'SecurityPerm', 'column' => array('code')))
    );

    $this->widgetSchema->setNameFormat('security_perm[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SecurityPerm';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['security_role_perm_list']))
    {
      $values = array();
      foreach ($this->object->getSecurityRolePerms() as $obj)
      {
        $values[] = $obj->getRoleId();
      }

      $this->setDefault('security_role_perm_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveSecurityRolePermList($con);
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
    $c->add(SecurityRolePermPeer::PERM_ID, $this->object->getPrimaryKey());
    SecurityRolePermPeer::doDelete($c, $con);

    $values = $this->getValue('security_role_perm_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new SecurityRolePerm();
        $obj->setPermId($this->object->getPrimaryKey());
        $obj->setRoleId($value);
        $obj->save();
      }
    }
  }

}
