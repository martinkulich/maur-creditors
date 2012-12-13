<?php

/**
 * Regulation filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseRegulationFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'creditor_fullname'     => new sfWidgetFormFilterInput(),
      'creditor_id'           => new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'add_empty' => true)),
      'contract_id'           => new sfWidgetFormPropelChoice(array('model' => 'Contract', 'add_empty' => true)),
      'contract_name'         => new sfWidgetFormFilterInput(),
      'regulation_year'       => new sfWidgetFormPropelChoice(array('model' => 'RegulationYear', 'add_empty' => true)),
      'start_balance'         => new sfWidgetFormFilterInput(),
      'contract_activated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'contract_balance'      => new sfWidgetFormFilterInput(),
      'regulation'            => new sfWidgetFormFilterInput(),
      'paid'                  => new sfWidgetFormFilterInput(),
      'paid_for_current_year' => new sfWidgetFormFilterInput(),
      'capitalized'           => new sfWidgetFormFilterInput(),
      'unpaid'                => new sfWidgetFormFilterInput(),
      'unpaid_in_past'        => new sfWidgetFormFilterInput(),
      'end_balance'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'creditor_fullname'     => new sfValidatorPass(array('required' => false)),
      'creditor_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Creditor', 'column' => 'id')),
      'contract_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Contract', 'column' => 'id')),
      'contract_name'         => new sfValidatorPass(array('required' => false)),
      'regulation_year'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'RegulationYear', 'column' => 'id')),
      'start_balance'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'contract_activated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'contract_balance'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'regulation'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'paid'                  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'paid_for_current_year' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'capitalized'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'unpaid'                => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'unpaid_in_past'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'end_balance'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('regulation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Regulation';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Text',
      'creditor_fullname'     => 'Text',
      'creditor_id'           => 'ForeignKey',
      'contract_id'           => 'ForeignKey',
      'contract_name'         => 'Text',
      'regulation_year'       => 'ForeignKey',
      'start_balance'         => 'Number',
      'contract_activated_at' => 'Date',
      'contract_balance'      => 'Number',
      'regulation'            => 'Number',
      'paid'                  => 'Number',
      'paid_for_current_year' => 'Number',
      'capitalized'           => 'Number',
      'unpaid'                => 'Number',
      'unpaid_in_past'        => 'Number',
      'end_balance'           => 'Number',
    );
  }
}
