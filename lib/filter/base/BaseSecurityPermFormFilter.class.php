<?php

/**
 * SecurityPerm filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseSecurityPermFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'code'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_public'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'security_role_perm_list' => new sfWidgetFormPropelChoice(array('model' => 'SecurityRole', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'code'                    => new sfValidatorPass(array('required' => false)),
      'name'                    => new sfValidatorPass(array('required' => false)),
      'is_public'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'security_role_perm_list' => new sfValidatorPropelChoice(array('model' => 'SecurityRole', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('security_perm_filters[%s]');

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

    $criteria->addJoin(SecurityRolePermPeer::PERM_ID, SecurityPermPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(SecurityRolePermPeer::ROLE_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(SecurityRolePermPeer::ROLE_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'SecurityPerm';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'code'                    => 'Text',
      'name'                    => 'Text',
      'is_public'               => 'Boolean',
      'security_role_perm_list' => 'ManyKey',
    );
  }
}
