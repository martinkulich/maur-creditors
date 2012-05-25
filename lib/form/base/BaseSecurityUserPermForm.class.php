<?php

/**
 * SecurityUserPerm form base class.
 *
 * @method SecurityUserPerm getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseSecurityUserPermForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'perm_id' => new sfWidgetFormPropelChoice(array('model' => 'SecurityPerm', 'add_empty' => false)),
      'user_id' => new sfWidgetFormPropelChoice(array('model' => 'SecurityUser', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'perm_id' => new sfValidatorPropelChoice(array('model' => 'SecurityPerm', 'column' => 'id')),
      'user_id' => new sfValidatorPropelChoice(array('model' => 'SecurityUser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('security_user_perm[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SecurityUserPerm';
  }


}
