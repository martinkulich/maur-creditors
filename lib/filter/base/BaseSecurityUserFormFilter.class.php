<?php

/**
 * SecurityUser filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseSecurityUserFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'email'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'password'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'firstname'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'surname'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'phone'                    => new sfWidgetFormFilterInput(),
      'active'                   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_super_admin'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'security_user_group_list' => new sfWidgetFormPropelChoice(array('model' => 'SecurityGroup', 'add_empty' => true)),
      'security_user_role_list'  => new sfWidgetFormPropelChoice(array('model' => 'SecurityRole', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'email'                    => new sfValidatorPass(array('required' => false)),
      'password'                 => new sfValidatorPass(array('required' => false)),
      'firstname'                => new sfValidatorPass(array('required' => false)),
      'surname'                  => new sfValidatorPass(array('required' => false)),
      'phone'                    => new sfValidatorPass(array('required' => false)),
      'active'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_super_admin'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'security_user_group_list' => new sfValidatorPropelChoice(array('model' => 'SecurityGroup', 'required' => false)),
      'security_user_role_list'  => new sfValidatorPropelChoice(array('model' => 'SecurityRole', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('security_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addSecurityUserGroupListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(SecurityUserGroupPeer::USER_ID, SecurityUserPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(SecurityUserGroupPeer::GROUP_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(SecurityUserGroupPeer::GROUP_ID, $value));
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

    $criteria->addJoin(SecurityUserRolePeer::USER_ID, SecurityUserPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(SecurityUserRolePeer::ROLE_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(SecurityUserRolePeer::ROLE_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'SecurityUser';
  }

  public function getFields()
  {
    return array(
      'id'                       => 'Number',
      'email'                    => 'Text',
      'password'                 => 'Text',
      'firstname'                => 'Text',
      'surname'                  => 'Text',
      'phone'                    => 'Text',
      'active'                   => 'Boolean',
      'is_super_admin'           => 'Boolean',
      'security_user_group_list' => 'ManyKey',
      'security_user_role_list'  => 'ManyKey',
    );
  }
}
