<?php

/**
 * Regulation form base class.
 *
 * @method Regulation getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseRegulationForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                                  => new sfWidgetFormInputHidden(),
      'creditor_firstname'                  => new sfWidgetFormInputText(),
      'creditor_lastname'                   => new sfWidgetFormInputText(),
      'contract_id'                         => new sfWidgetFormInputText(),
      'contract_name'                       => new sfWidgetFormInputText(),
      'settlement_year'                     => new sfWidgetFormInputText(),
      'start_balance'                       => new sfWidgetFormInputText(),
      'contract_activated_at'               => new sfWidgetFormDate(),
      'contract_balance'                    => new sfWidgetFormInputText(),
      'regulation'                          => new sfWidgetFormInputText(),
      'paid'                                => new sfWidgetFormInputText(),
      'paid_for_current_year'               => new sfWidgetFormInputText(),
      'capitalized'                         => new sfWidgetFormInputText(),
      'teoretically_to_pay_in_current_year' => new sfWidgetFormInputText(),
      'end_balance'                         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                                  => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'creditor_firstname'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'creditor_lastname'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'contract_id'                         => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'contract_name'                       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'settlement_year'                     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'start_balance'                       => new sfValidatorNumber(array('required' => false)),
      'contract_activated_at'               => new sfValidatorDate(array('required' => false)),
      'contract_balance'                    => new sfValidatorNumber(array('required' => false)),
      'regulation'                          => new sfValidatorNumber(array('required' => false)),
      'paid'                                => new sfValidatorNumber(array('required' => false)),
      'paid_for_current_year'               => new sfValidatorNumber(array('required' => false)),
      'capitalized'                         => new sfValidatorNumber(array('required' => false)),
      'teoretically_to_pay_in_current_year' => new sfValidatorNumber(array('required' => false)),
      'end_balance'                         => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('regulation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Regulation';
  }


}
