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
      'contract_id'     => new sfWidgetFormPropelChoice(array('model' => 'Contract', 'add_empty' => true)),
      'date'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'interest'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'capitalized'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'balance'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'note'            => new sfWidgetFormFilterInput(),
      'settlement_type' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'manual_interest' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'manual_balance'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'contract_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Contract', 'column' => 'id')),
      'date'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'interest'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'capitalized'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'balance'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'note'            => new sfValidatorPass(array('required' => false)),
      'settlement_type' => new sfValidatorPass(array('required' => false)),
      'manual_interest' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'manual_balance'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
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
      'id'              => 'Number',
      'contract_id'     => 'ForeignKey',
      'date'            => 'Date',
      'interest'        => 'Number',
      'capitalized'     => 'Number',
      'balance'         => 'Number',
      'note'            => 'Text',
      'settlement_type' => 'Text',
      'manual_interest' => 'Boolean',
      'manual_balance'  => 'Boolean',
    );
  }
}
