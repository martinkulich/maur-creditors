<?php

/**
 * Contract filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseContractFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'creditor_id'                   => new sfWidgetFormPropelChoice(array('model' => 'Subject', 'add_empty' => true)),
      'created_at'                    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'activated_at'                  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'period'                        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'interest_rate'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'amount'                        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name'                          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'closed_at'                     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'note'                          => new sfWidgetFormFilterInput(),
      'currency_code'                 => new sfWidgetFormPropelChoice(array('model' => 'Currency', 'add_empty' => true)),
      'first_settlement_date'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'capitalize'                    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'contract_type_id'              => new sfWidgetFormPropelChoice(array('model' => 'ContractType', 'add_empty' => true)),
      'document'                      => new sfWidgetFormFilterInput(),
      'debtor_id'                     => new sfWidgetFormPropelChoice(array('model' => 'Subject', 'add_empty' => true)),
      'contract_excluded_report_list' => new sfWidgetFormPropelChoice(array('model' => 'Report', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'creditor_id'                   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Subject', 'column' => 'id')),
      'created_at'                    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'activated_at'                  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'period'                        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'interest_rate'                 => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'amount'                        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'name'                          => new sfValidatorPass(array('required' => false)),
      'closed_at'                     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'note'                          => new sfValidatorPass(array('required' => false)),
      'currency_code'                 => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Currency', 'column' => 'code')),
      'first_settlement_date'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'capitalize'                    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'contract_type_id'              => new sfValidatorPropelChoice(array('required' => false, 'model' => 'ContractType', 'column' => 'id')),
      'document'                      => new sfValidatorPass(array('required' => false)),
      'debtor_id'                     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Subject', 'column' => 'id')),
      'contract_excluded_report_list' => new sfValidatorPropelChoice(array('model' => 'Report', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('contract_filters[%s]');

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

    $criteria->addJoin(ContractExcludedReportPeer::CONTRACT_ID, ContractPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(ContractExcludedReportPeer::REPORT_CODE, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(ContractExcludedReportPeer::REPORT_CODE, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Contract';
  }

  public function getFields()
  {
    return array(
      'id'                            => 'Number',
      'creditor_id'                   => 'ForeignKey',
      'created_at'                    => 'Date',
      'activated_at'                  => 'Date',
      'period'                        => 'Number',
      'interest_rate'                 => 'Number',
      'amount'                        => 'Number',
      'name'                          => 'Text',
      'closed_at'                     => 'Date',
      'note'                          => 'Text',
      'currency_code'                 => 'ForeignKey',
      'first_settlement_date'         => 'Date',
      'capitalize'                    => 'Boolean',
      'contract_type_id'              => 'ForeignKey',
      'document'                      => 'Text',
      'debtor_id'                     => 'ForeignKey',
      'contract_excluded_report_list' => 'ManyKey',
    );
  }
}
