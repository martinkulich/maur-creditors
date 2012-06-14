<?php

/**
 * Creditor form base class.
 *
 * @method Creditor getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseCreditorForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'creditor_type_code'    => new sfWidgetFormInputText(),
      'identification_number' => new sfWidgetFormInputText(),
      'firstname'             => new sfWidgetFormInputText(),
      'lastname'              => new sfWidgetFormInputText(),
      'email'                 => new sfWidgetFormInputText(),
      'phone'                 => new sfWidgetFormInputText(),
      'bank_account'          => new sfWidgetFormInputText(),
      'city'                  => new sfWidgetFormInputText(),
      'street'                => new sfWidgetFormInputText(),
      'zip'                   => new sfWidgetFormInputText(),
      'note'                  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'creditor_type_code'    => new sfValidatorString(array('max_length' => 255)),
      'identification_number' => new sfValidatorString(array('max_length' => 255)),
      'firstname'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'lastname'              => new sfValidatorString(array('max_length' => 255)),
      'email'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'phone'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'bank_account'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'street'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'zip'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'note'                  => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('creditor[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Creditor';
  }


}
