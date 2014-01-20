<?php

/**
 * OutgoingPayment form base class.
 *
 * @method OutgoingPayment getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseOutgoingPaymentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'bank_account_id'       => new sfWidgetFormPropelChoice(array('model' => 'BankAccount', 'add_empty' => true)),
      'amount'                => new sfWidgetFormInputText(),
      'date'                  => new sfWidgetFormDate(),
      'note'                  => new sfWidgetFormTextarea(),
      'currency_code'         => new sfWidgetFormPropelChoice(array('model' => 'Currency', 'add_empty' => false)),
      'creditor_id'           => new sfWidgetFormPropelChoice(array('model' => 'Subject', 'add_empty' => false)),
      'cash'                  => new sfWidgetFormInputCheckbox(),
      'receiver_bank_account' => new sfWidgetFormInputText(),
      'refundation'           => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'bank_account_id'       => new sfValidatorPropelChoice(array('model' => 'BankAccount', 'column' => 'id', 'required' => false)),
      'amount'                => new sfValidatorNumber(),
      'date'                  => new sfValidatorDate(),
      'note'                  => new sfValidatorString(array('required' => false)),
      'currency_code'         => new sfValidatorPropelChoice(array('model' => 'Currency', 'column' => 'code')),
      'creditor_id'           => new sfValidatorPropelChoice(array('model' => 'Subject', 'column' => 'id')),
      'cash'                  => new sfValidatorBoolean(array('required' => false)),
      'receiver_bank_account' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'refundation'           => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('outgoing_payment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'OutgoingPayment';
  }


}
