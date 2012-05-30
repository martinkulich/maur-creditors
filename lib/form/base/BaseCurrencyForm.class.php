<?php

/**
 * Currency form base class.
 *
 * @method Currency getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseCurrencyForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'code' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'code' => new sfValidatorChoice(array('choices' => array($this->getObject()->getCode()), 'empty_value' => $this->getObject()->getCode(), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('currency[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Currency';
  }


}
