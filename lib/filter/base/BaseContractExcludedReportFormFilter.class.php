<?php

/**
 * ContractExcludedReport filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseContractExcludedReportFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
    ));

    $this->setValidators(array(
    ));

    $this->widgetSchema->setNameFormat('contract_excluded_report_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ContractExcludedReport';
  }

  public function getFields()
  {
    return array(
      'contract_id' => 'ForeignKey',
      'report_code' => 'ForeignKey',
    );
  }
}
