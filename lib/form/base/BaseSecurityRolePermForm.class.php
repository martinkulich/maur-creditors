<?php

/**
 * SecurityRolePerm form base class.
 *
 * @method SecurityRolePerm getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseSecurityRolePermForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'role_id' => new sfWidgetFormInputHidden(),
      'perm_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'role_id' => new sfValidatorPropelChoice(array('model' => 'SecurityRole', 'column' => 'id', 'required' => false)),
      'perm_id' => new sfValidatorPropelChoice(array('model' => 'SecurityPerm', 'column' => 'id', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('security_role_perm[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SecurityRolePerm';
  }


}
