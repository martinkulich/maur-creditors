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
      'id'              => new sfWidgetFormInputHidden(),
      'contract_id'     => new sfWidgetFormPropelChoice(array('model' => 'Contract', 'add_empty' => false)),
      'date'            => new sfWidgetFormDate(),
      'interest'        => new sfWidgetFormInputText(),
      'capitalized'     => new sfWidgetFormInputText(),
      'balance'         => new sfWidgetFormInputText(),
      'note'            => new sfWidgetFormTextarea(),
      'settlement_type' => new sfWidgetFormInputText(),
      'manual_interest' => new sfWidgetFormInputCheckbox(),
      'manual_balance'  => new sfWidgetFormInputCheckbox(),
      'payment_id'      => new sfWidgetFormPropelChoice(array('model' => 'Payment', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'contract_id'     => new sfValidatorPropelChoice(array('model' => 'Contract', 'column' => 'id')),
      'date'            => new sfValidatorDate(),
      'interest'        => new sfValidatorNumber(),
      'capitalized'     => new sfValidatorNumber(),
      'balance'         => new sfValidatorNumber(),
      'note'            => new sfValidatorString(array('required' => false)),
      'settlement_type' => new sfValidatorString(array('max_length' => 255)),
      'manual_interest' => new sfValidatorBoolean(),
      'manual_balance'  => new sfValidatorBoolean(),
      'payment_id'      => new sfValidatorPropelChoice(array('model' => 'Payment', 'column' => 'id', 'required' => false)),
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
