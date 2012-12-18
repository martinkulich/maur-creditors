<?php

/**
 * OutgoingPayment filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseOutgoingPaymentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'bank_account_id' => new sfWidgetFormPropelChoice(array('model' => 'BankAccount', 'add_empty' => true)),
      'amount'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'currency_code'   => new sfWidgetFormPropelChoice(array('model' => 'Currency', 'add_empty' => true)),
      'creditor_id'     => new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'add_empty' => true)),
      'note'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'bank_account_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'BankAccount', 'column' => 'id')),
      'amount'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'date'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'currency_code'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Currency', 'column' => 'code')),
      'creditor_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Creditor', 'column' => 'id')),
      'note'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('outgoing_payment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'OutgoingPayment';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'bank_account_id' => 'ForeignKey',
      'amount'          => 'Number',
      'date'            => 'Date',
      'currency_code'   => 'ForeignKey',
      'creditor_id'     => 'ForeignKey',
      'note'            => 'Text',
    );
  }
}