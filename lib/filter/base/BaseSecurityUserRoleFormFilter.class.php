<?php

/**
 * SecurityUserRole filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseSecurityUserRoleFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
    ));

    $this->setValidators(array(
    ));

    $this->widgetSchema->setNameFormat('security_user_role_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SecurityUserRole';
  }

  public function getFields()
  {
    return array(
      'user_id' => 'ForeignKey',
      'role_id' => 'ForeignKey',
    );
  }
}
