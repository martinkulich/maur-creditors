<?php

/**
 * ContractExcludedReport form base class.
 *
 * @method ContractExcludedReport getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseContractExcludedReportForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'contract_id' => new sfWidgetFormInputHidden(),
      'report_code' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'contract_id' => new sfValidatorPropelChoice(array('model' => 'Contract', 'column' => 'id', 'required' => false)),
      'report_code' => new sfValidatorPropelChoice(array('model' => 'Report', 'column' => 'code', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('contract_excluded_report[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ContractExcludedReport';
  }


}
