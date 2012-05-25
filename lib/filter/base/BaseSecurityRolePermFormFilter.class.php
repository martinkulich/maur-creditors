<?php

/**
 * SecurityRolePerm filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseSecurityRolePermFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
    ));

    $this->setValidators(array(
    ));

    $this->widgetSchema->setNameFormat('security_role_perm_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SecurityRolePerm';
  }

  public function getFields()
  {
    return array(
      'role_id' => 'ForeignKey',
      'perm_id' => 'ForeignKey',
    );
  }
}
