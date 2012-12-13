<?php

/**
 * BankAccount filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseBankAccountFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'number' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'   => new sfValidatorPass(array('required' => false)),
      'number' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('bank_account_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'BankAccount';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'name'   => 'Text',
      'number' => 'Text',
    );
  }
}
