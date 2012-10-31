<?php

/**
 * Settlement form base class.
 *
 * @method Settlement getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseSettlementForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'contract_id'       => new sfWidgetFormPropelChoice(array('model' => 'Contract', 'add_empty' => false)),
      'date'              => new sfWidgetFormDate(),
      'interest'          => new sfWidgetFormInputText(),
      'paid'              => new sfWidgetFormInputText(),
      'capitalized'       => new sfWidgetFormInputText(),
      'balance'           => new sfWidgetFormInputText(),
      'balance_reduction' => new sfWidgetFormInputText(),
      'note'              => new sfWidgetFormTextarea(),
      'bank_account'      => new sfWidgetFormInputText(),
      'cash'              => new sfWidgetFormInputCheckbox(),
      'settlement_type'   => new sfWidgetFormInputText(),
      'manual_interest'   => new sfWidgetFormInputCheckbox(),
      'manual_balance'    => new sfWidgetFormInputCheckbox(),
      'date_of_payment'   => new sfWidgetFormDate(),
      'currency_rate'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'contract_id'       => new sfValidatorPropelChoice(array('model' => 'Contract', 'column' => 'id')),
      'date'              => new sfValidatorDate(),
      'interest'          => new sfValidatorNumber(),
      'paid'              => new sfValidatorNumber(),
      'capitalized'       => new sfValidatorNumber(),
      'balance'           => new sfValidatorNumber(),
      'balance_reduction' => new sfValidatorNumber(),
      'note'              => new sfValidatorString(array('required' => false)),
      'bank_account'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'cash'              => new sfValidatorBoolean(),
      'settlement_type'   => new sfValidatorString(array('max_length' => 255)),
      'manual_interest'   => new sfValidatorBoolean(),
      'manual_balance'    => new sfValidatorBoolean(),
      'date_of_payment'   => new sfValidatorDate(array('required' => false)),
      'currency_rate'     => new sfValidatorNumber(),
    ));

    $this->widgetSchema->setNameFormat('settlement[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Settlement';
  }


}
