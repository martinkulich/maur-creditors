<?php

/**
 * SecurityUserPerm filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseSecurityUserPermFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'perm_id' => new sfWidgetFormPropelChoice(array('model' => 'SecurityPerm', 'add_empty' => true)),
      'user_id' => new sfWidgetFormPropelChoice(array('model' => 'SecurityUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'perm_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'SecurityPerm', 'column' => 'id')),
      'user_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'SecurityUser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('security_user_perm_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SecurityUserPerm';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'perm_id' => 'ForeignKey',
      'user_id' => 'ForeignKey',
    );
  }
}
