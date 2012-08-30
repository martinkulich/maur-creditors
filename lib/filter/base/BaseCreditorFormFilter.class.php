<?php

/**
 * Creditor filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseCreditorFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'creditor_type_code'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'identification_number' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'firstname'             => new sfWidgetFormFilterInput(),
      'lastname'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'email'                 => new sfWidgetFormFilterInput(),
      'phone'                 => new sfWidgetFormFilterInput(),
      'bank_account'          => new sfWidgetFormFilterInput(),
      'city'                  => new sfWidgetFormFilterInput(),
      'street'                => new sfWidgetFormFilterInput(),
      'zip'                   => new sfWidgetFormFilterInput(),
      'note'                  => new sfWidgetFormFilterInput(),
      'birth_date'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'creditor_type_code'    => new sfValidatorPass(array('required' => false)),
      'identification_number' => new sfValidatorPass(array('required' => false)),
      'firstname'             => new sfValidatorPass(array('required' => false)),
      'lastname'              => new sfValidatorPass(array('required' => false)),
      'email'                 => new sfValidatorPass(array('required' => false)),
      'phone'                 => new sfValidatorPass(array('required' => false)),
      'bank_account'          => new sfValidatorPass(array('required' => false)),
      'city'                  => new sfValidatorPass(array('required' => false)),
      'street'                => new sfValidatorPass(array('required' => false)),
      'zip'                   => new sfValidatorPass(array('required' => false)),
      'note'                  => new sfValidatorPass(array('required' => false)),
      'birth_date'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('creditor_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Creditor';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'creditor_type_code'    => 'Text',
      'identification_number' => 'Text',
      'firstname'             => 'Text',
      'lastname'              => 'Text',
      'email'                 => 'Text',
      'phone'                 => 'Text',
      'bank_account'          => 'Text',
      'city'                  => 'Text',
      'street'                => 'Text',
      'zip'                   => 'Text',
      'note'                  => 'Text',
      'birth_date'            => 'Date',
    );
  }
}
