<?php

/**
 * Payment filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BasePaymentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'contract_id'         => new sfWidgetFormPropelChoice(array('model' => 'Contract', 'add_empty' => true)),
      'date'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'amount'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'note'                => new sfWidgetFormFilterInput(),
      'cash'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'sender_bank_account' => new sfWidgetFormFilterInput(),
      'payment_type'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'bank_account_id'     => new sfWidgetFormPropelChoice(array('model' => 'BankAccount', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'contract_id'         => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Contract', 'column' => 'id')),
      'date'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'amount'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'note'                => new sfValidatorPass(array('required' => false)),
      'cash'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'sender_bank_account' => new sfValidatorPass(array('required' => false)),
      'payment_type'        => new sfValidatorPass(array('required' => false)),
      'bank_account_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'BankAccount', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('payment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Payment';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'contract_id'         => 'ForeignKey',
      'date'                => 'Date',
      'amount'              => 'Number',
      'note'                => 'Text',
      'cash'                => 'Boolean',
      'sender_bank_account' => 'Text',
      'payment_type'        => 'Text',
      'bank_account_id'     => 'ForeignKey',
    );
  }
}
