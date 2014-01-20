<?php

/**
 * Gift filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseGiftFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'creditor_id' => new sfWidgetFormPropelChoice(array('model' => 'Subject', 'add_empty' => true)),
      'name'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'note'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'creditor_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Subject', 'column' => 'id')),
      'name'        => new sfValidatorPass(array('required' => false)),
      'date'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'note'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gift_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Gift';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'creditor_id' => 'ForeignKey',
      'name'        => 'Text',
      'date'        => 'Date',
      'note'        => 'Text',
    );
  }
}
