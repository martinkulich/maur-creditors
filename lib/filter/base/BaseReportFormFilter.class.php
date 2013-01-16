<?php

/**
 * Report filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseReportFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'contract_excluded_report_list' => new sfWidgetFormPropelChoice(array('model' => 'Contract', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'                          => new sfValidatorPass(array('required' => false)),
      'contract_excluded_report_list' => new sfValidatorPropelChoice(array('model' => 'Contract', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('report_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addContractExcludedReportListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(ContractExcludedReportPeer::REPORT_CODE, ReportPeer::CODE);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(ContractExcludedReportPeer::CONTRACT_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(ContractExcludedReportPeer::CONTRACT_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Report';
  }

  public function getFields()
  {
    return array(
      'code'                          => 'Text',
      'name'                          => 'Text',
      'contract_excluded_report_list' => 'ManyKey',
    );
  }
}
