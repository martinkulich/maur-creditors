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
      'bank_account_id'       => new sfWidgetFormPropelChoice(array('model' => 'BankAccount', 'add_empty' => true)),
      'amount'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date'                  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'note'                  => new sfWidgetFormFilterInput(),
      'currency_code'         => new sfWidgetFormPropelChoice(array('model' => 'Currency', 'add_empty' => true)),
      'creditor_id'           => new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'add_empty' => true)),
      'cash'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'receiver_bank_account' => new sfWidgetFormFilterInput(),
      'refundation'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'bank_account_id'       => new sfValidatorPropelChoice(array('required' => false, 'model' => 'BankAccount', 'column' => 'id')),
      'amount'                => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'date'                  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'note'                  => new sfValidatorPass(array('required' => false)),
      'currency_code'         => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Currency', 'column' => 'code')),
      'creditor_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Creditor', 'column' => 'id')),
      'cash'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'receiver_bank_account' => new sfValidatorPass(array('required' => false)),
      'refundation'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
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
      'id'                    => 'Number',
      'bank_account_id'       => 'ForeignKey',
      'amount'                => 'Number',
      'date'                  => 'Date',
      'note'                  => 'Text',
      'currency_code'         => 'ForeignKey',
      'creditor_id'           => 'ForeignKey',
      'cash'                  => 'Boolean',
      'receiver_bank_account' => 'Text',
      'refundation'           => 'Number',
    );
  }
}
