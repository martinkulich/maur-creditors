<?php

/**
 * Settlement filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseSettlementFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'contract_id'       => new sfWidgetFormPropelChoice(array('model' => 'Contract', 'add_empty' => true)),
      'date'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'interest'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'paid'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'capitalized'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'balance'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'balance_reduction' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'contract_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Contract', 'column' => 'id')),
      'date'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'interest'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'paid'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'capitalized'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'balance'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'balance_reduction' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('settlement_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Settlement';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'contract_id'       => 'ForeignKey',
      'date'              => 'Date',
      'interest'          => 'Number',
      'paid'              => 'Number',
      'capitalized'       => 'Number',
      'balance'           => 'Number',
      'balance_reduction' => 'Number',
    );
  }
}
