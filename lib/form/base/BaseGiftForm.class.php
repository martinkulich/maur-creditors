<?php

/**
 * Gift form base class.
 *
 * @method Gift getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseGiftForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'creditor_id' => new sfWidgetFormPropelChoice(array('model' => 'Subject', 'add_empty' => false)),
      'name'        => new sfWidgetFormInputText(),
      'date'        => new sfWidgetFormDate(),
      'note'        => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'creditor_id' => new sfValidatorPropelChoice(array('model' => 'Subject', 'column' => 'id')),
      'name'        => new sfValidatorString(array('max_length' => 255)),
      'date'        => new sfValidatorDate(),
      'note'        => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gift[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Gift';
  }


}
