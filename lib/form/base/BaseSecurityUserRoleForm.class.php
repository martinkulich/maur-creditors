<?php

/**
 * SecurityUserRole form base class.
 *
 * @method SecurityUserRole getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseSecurityUserRoleForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id' => new sfWidgetFormInputHidden(),
      'role_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'user_id' => new sfValidatorPropelChoice(array('model' => 'SecurityUser', 'column' => 'id', 'required' => false)),
      'role_id' => new sfValidatorPropelChoice(array('model' => 'SecurityRole', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('security_user_role[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SecurityUserRole';
  }


}
