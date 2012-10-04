<?php

/**
 * Unpaid filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseUnpaidFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'creditor_fullname' => new sfWidgetFormFilterInput(),
      'creditor_id'       => new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'add_empty' => true)),
      'contract_id'       => new sfWidgetFormPropelChoice(array('model' => 'Contract', 'add_empty' => true)),
      'contract_name'     => new sfWidgetFormFilterInput(),
      'settlement_date'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'creditor_unpaid'   => new sfWidgetFormFilterInput(),
      'contract_unpaid'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'creditor_fullname' => new sfValidatorPass(array('required' => false)),
      'creditor_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Creditor', 'column' => 'id')),
      'contract_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Contract', 'column' => 'id')),
      'contract_name'     => new sfValidatorPass(array('required' => false)),
      'settlement_date'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creditor_unpaid'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'contract_unpaid'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('unpaid_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Unpaid';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Text',
      'creditor_fullname' => 'Text',
      'creditor_id'       => 'ForeignKey',
      'contract_id'       => 'ForeignKey',
      'contract_name'     => 'Text',
      'settlement_date'   => 'Date',
      'creditor_unpaid'   => 'Number',
      'contract_unpaid'   => 'Number',
    );
  }
}
