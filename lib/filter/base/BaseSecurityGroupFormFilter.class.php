<?php

/**
 * SecurityGroup filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseSecurityGroupFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_public'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'security_user_group_list' => new sfWidgetFormPropelChoice(array('model' => 'SecurityUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'                     => new sfValidatorPass(array('required' => false)),
      'is_public'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'security_user_group_list' => new sfValidatorPropelChoice(array('model' => 'SecurityUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('security_group_filters[%s]');

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

    $criteria->addJoin(SecurityUserGroupPeer::GROUP_ID, SecurityGroupPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(SecurityUserGroupPeer::USER_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(SecurityUserGroupPeer::USER_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'SecurityGroup';
  }

  public function getFields()
  {
    return array(
      'id'                       => 'Number',
      'name'                     => 'Text',
      'is_public'                => 'Boolean',
      'security_user_group_list' => 'ManyKey',
    );
  }
}
