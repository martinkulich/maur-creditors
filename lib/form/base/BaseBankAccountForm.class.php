<?php

/**
 * BankAccount form base class.
 *
 * @method BankAccount getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseBankAccountForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'     => new sfWidgetFormInputHidden(),
      'name'   => new sfWidgetFormInputText(),
      'number' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'     => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'name'   => new sfValidatorString(array('max_length' => 255)),
      'number' => new sfValidatorString(array('max_length' => 255)),
    ));

    $this->widgetSchema->setNameFormat('bank_account[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'BankAccount';
  }


}
