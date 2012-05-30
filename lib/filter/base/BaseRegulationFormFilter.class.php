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
      'creditor_firstname'                  => new sfWidgetFormFilterInput(),
      'creditor_lastname'                   => new sfWidgetFormFilterInput(),
      'contract_id'                         => new sfWidgetFormFilterInput(),
      'contract_name'                       => new sfWidgetFormFilterInput(),
      'settlement_year'                     => new sfWidgetFormFilterInput(),
      'start_balance'                       => new sfWidgetFormFilterInput(),
      'contract_activated_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'contract_balance'                    => new sfWidgetFormFilterInput(),
      'regulation'                          => new sfWidgetFormFilterInput(),
      'paid'                                => new sfWidgetFormFilterInput(),
      'paid_for_current_year'               => new sfWidgetFormFilterInput(),
      'capitalized'                         => new sfWidgetFormFilterInput(),
      'teoretically_to_pay_in_current_year' => new sfWidgetFormFilterInput(),
      'end_balance'                         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'creditor_firstname'                  => new sfValidatorPass(array('required' => false)),
      'creditor_lastname'                   => new sfValidatorPass(array('required' => false)),
      'contract_id'                         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'contract_name'                       => new sfValidatorPass(array('required' => false)),
      'settlement_year'                     => new sfValidatorPass(array('required' => false)),
      'start_balance'                       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'contract_activated_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'contract_balance'                    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'regulation'                          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'paid'                                => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'paid_for_current_year'               => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'capitalized'                         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'teoretically_to_pay_in_current_year' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'end_balance'                         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
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
      'id'                                  => 'Text',
      'creditor_firstname'                  => 'Text',
      'creditor_lastname'                   => 'Text',
      'contract_id'                         => 'Number',
      'contract_name'                       => 'Text',
      'settlement_year'                     => 'Text',
      'start_balance'                       => 'Number',
      'contract_activated_at'               => 'Date',
      'contract_balance'                    => 'Number',
      'regulation'                          => 'Number',
      'paid'                                => 'Number',
      'paid_for_current_year'               => 'Number',
      'capitalized'                         => 'Number',
      'teoretically_to_pay_in_current_year' => 'Number',
      'end_balance'                         => 'Number',
    );
  }
}
