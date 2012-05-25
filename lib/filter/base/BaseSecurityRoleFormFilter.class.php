<?php

/**
 * SecurityRole filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseSecurityRoleFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                    => new sfWidgetFormFilterInput(),
      'security_role_perm_list' => new sfWidgetFormPropelChoice(array('model' => 'SecurityPerm', 'add_empty' => true)),
      'security_user_role_list' => new sfWidgetFormPropelChoice(array('model' => 'SecurityUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'                    => new sfValidatorPass(array('required' => false)),
      'security_role_perm_list' => new sfValidatorPropelChoice(array('model' => 'SecurityPerm', 'required' => false)),
      'security_user_role_list' => new sfValidatorPropelChoice(array('model' => 'SecurityUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('security_role_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addSecurityRolePermListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(SecurityRolePermPeer::ROLE_ID, SecurityRolePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(SecurityRolePermPeer::PERM_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(SecurityRolePermPeer::PERM_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function addSecurityUserRoleListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(SecurityUserRolePeer::ROLE_ID, SecurityRolePeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(SecurityUserRolePeer::USER_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(SecurityUserRolePeer::USER_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'SecurityRole';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'name'                    => 'Text',
      'security_role_perm_list' => 'ManyKey',
      'security_user_role_list' => 'ManyKey',
    );
  }
}
