<?php

/**
 * Unpaid form base class.
 *
 * @method Unpaid getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseUnpaidForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'creditor_fullname' => new sfWidgetFormInputText(),
      'creditor_id'       => new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'add_empty' => true)),
      'contract_id'       => new sfWidgetFormPropelChoice(array('model' => 'Contract', 'add_empty' => true)),
      'contract_name'     => new sfWidgetFormInputText(),
      'settlement_date'   => new sfWidgetFormDate(),
      'creditor_unpaid'   => new sfWidgetFormInputText(),
      'contract_unpaid'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'creditor_fullname' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'creditor_id'       => new sfValidatorPropelChoice(array('model' => 'Creditor', 'column' => 'id', 'required' => false)),
      'contract_id'       => new sfValidatorPropelChoice(array('model' => 'Contract', 'column' => 'id', 'required' => false)),
      'contract_name'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'settlement_date'   => new sfValidatorDate(array('required' => false)),
      'creditor_unpaid'   => new sfValidatorNumber(array('required' => false)),
      'contract_unpaid'   => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('unpaid[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Unpaid';
  }


}
