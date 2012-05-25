<?php

/**
 * SecurityGroup form base class.
 *
 * @method SecurityGroup getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseSecurityGroupForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'name'                     => new sfWidgetFormInputText(),
      'is_public'                => new sfWidgetFormInputCheckbox(),
      'security_user_group_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'SecurityUser')),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'name'                     => new sfValidatorString(array('max_length' => 255)),
      'is_public'                => new sfValidatorBoolean(),
      'security_user_group_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'SecurityUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('security_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SecurityGroup';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['security_user_group_list']))
    {
      $values = array();
      foreach ($this->object->getSecurityUserGroups() as $obj)
      {
        $values[] = $obj->getUserId();
      }

      $this->setDefault('security_user_group_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveSecurityUserGroupList($con);
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
    $c->add(SecurityUserGroupPeer::GROUP_ID, $this->object->getPrimaryKey());
    SecurityUserGroupPeer::doDelete($c, $con);

    $values = $this->getValue('security_user_group_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new SecurityUserGroup();
        $obj->setGroupId($this->object->getPrimaryKey());
        $obj->setUserId($value);
        $obj->save();
      }
    }
  }

}
