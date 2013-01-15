<?php

/**
 * Allocation form base class.
 *
 * @method Allocation getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseAllocationForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'paid'                => new sfWidgetFormInputText(),
      'balance_reduction'   => new sfWidgetFormInputText(),
      'outgoing_payment_id' => new sfWidgetFormPropelChoice(array('model' => 'OutgoingPayment', 'add_empty' => false)),
      'settlement_id'       => new sfWidgetFormPropelChoice(array('model' => 'Settlement', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'paid'                => new sfValidatorNumber(),
      'balance_reduction'   => new sfValidatorNumber(),
      'outgoing_payment_id' => new sfValidatorPropelChoice(array('model' => 'OutgoingPayment', 'column' => 'id')),
      'settlement_id'       => new sfValidatorPropelChoice(array('model' => 'Settlement', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('allocation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Allocation';
  }


}
