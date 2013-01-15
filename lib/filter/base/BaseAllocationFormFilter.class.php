<?php

/**
 * Allocation filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseAllocationFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'paid'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'balance_reduction'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'outgoing_payment_id' => new sfWidgetFormPropelChoice(array('model' => 'OutgoingPayment', 'add_empty' => true)),
      'settlement_id'       => new sfWidgetFormPropelChoice(array('model' => 'Settlement', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'paid'                => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'balance_reduction'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'outgoing_payment_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'OutgoingPayment', 'column' => 'id')),
      'settlement_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Settlement', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('allocation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Allocation';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'paid'                => 'Number',
      'balance_reduction'   => 'Number',
      'outgoing_payment_id' => 'ForeignKey',
      'settlement_id'       => 'ForeignKey',
    );
  }
}
