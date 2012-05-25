<?php

/**
 * SecurityUserGroup form base class.
 *
 * @method SecurityUserGroup getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseSecurityUserGroupForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'  => new sfWidgetFormInputHidden(),
      'group_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'user_id'  => new sfValidatorPropelChoice(array('model' => 'SecurityUser', 'column' => 'id', 'required' => false)),
      'group_id' => new sfValidatorPropelChoice(array('model' => 'SecurityGroup', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('security_user_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SecurityUserGroup';
  }


}
