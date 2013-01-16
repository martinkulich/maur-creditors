<?php

/**
 * Payment form base class.
 *
 * @method Payment getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BasePaymentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'contract_id'         => new sfWidgetFormPropelChoice(array('model' => 'Contract', 'add_empty' => false)),
      'date'                => new sfWidgetFormDate(),
      'amount'              => new sfWidgetFormInputText(),
      'note'                => new sfWidgetFormTextarea(),
      'cash'                => new sfWidgetFormInputCheckbox(),
      'sender_bank_account' => new sfWidgetFormInputText(),
      'payment_type'        => new sfWidgetFormInputText(),
      'bank_account_id'     => new sfWidgetFormPropelChoice(array('model' => 'BankAccount', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'contract_id'         => new sfValidatorPropelChoice(array('model' => 'Contract', 'column' => 'id')),
      'date'                => new sfValidatorDate(),
      'amount'              => new sfValidatorNumber(),
      'note'                => new sfValidatorString(array('required' => false)),
      'cash'                => new sfValidatorBoolean(),
      'sender_bank_account' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'payment_type'        => new sfValidatorString(array('max_length' => 255)),
      'bank_account_id'     => new sfValidatorPropelChoice(array('model' => 'BankAccount', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('payment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Payment';
  }


}
