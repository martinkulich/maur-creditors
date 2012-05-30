<?php

/**
 * Contract filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseContractFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'creditor_id'   => new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'add_empty' => true)),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'activated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'period'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'interest_rate' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'amount'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'closed_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'creditor_id'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Creditor', 'column' => 'id')),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'activated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'period'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'interest_rate' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'amount'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'name'          => new sfValidatorPass(array('required' => false)),
      'closed_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('contract_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Contract';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'creditor_id'   => 'ForeignKey',
      'created_at'    => 'Date',
      'activated_at'  => 'Date',
      'period'        => 'Number',
      'interest_rate' => 'Number',
      'amount'        => 'Number',
      'name'          => 'Text',
      'closed_at'     => 'Date',
    );
  }
}
