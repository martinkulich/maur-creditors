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
      'id'                    => new sfWidgetFormInputHidden(),
      'creditor_fullname'     => new sfWidgetFormInputText(),
      'contract_id'           => new sfWidgetFormInputText(),
      'contract_name'         => new sfWidgetFormInputText(),
      'regulation_year'       => new sfWidgetFormInputText(),
      'start_balance'         => new sfWidgetFormInputText(),
      'contract_activated_at' => new sfWidgetFormDate(),
      'contract_balance'      => new sfWidgetFormInputText(),
      'regulation'            => new sfWidgetFormInputText(),
      'paid'                  => new sfWidgetFormInputText(),
      'paid_for_current_year' => new sfWidgetFormInputText(),
      'capitalized'           => new sfWidgetFormInputText(),
      'unpaid'                => new sfWidgetFormInputText(),
      'unpaid_in_past'        => new sfWidgetFormInputText(),
      'end_balance'           => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'creditor_fullname'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'contract_id'           => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647, 'required' => false)),
      'contract_name'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'regulation_year'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'start_balance'         => new sfValidatorNumber(array('required' => false)),
      'contract_activated_at' => new sfValidatorDate(array('required' => false)),
      'contract_balance'      => new sfValidatorNumber(array('required' => false)),
      'regulation'            => new sfValidatorNumber(array('required' => false)),
      'paid'                  => new sfValidatorNumber(array('required' => false)),
      'paid_for_current_year' => new sfValidatorNumber(array('required' => false)),
      'capitalized'           => new sfValidatorNumber(array('required' => false)),
      'unpaid'                => new sfValidatorNumber(array('required' => false)),
      'unpaid_in_past'        => new sfValidatorNumber(array('required' => false)),
      'end_balance'           => new sfValidatorNumber(array('required' => false)),
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
