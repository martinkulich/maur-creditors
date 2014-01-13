<?php

/**
 * Contract form base class.
 *
 * @method Contract getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseContractForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                            => new sfWidgetFormInputHidden(),
      'creditor_id'                   => new sfWidgetFormPropelChoice(array('model' => 'Creditor', 'add_empty' => false)),
      'created_at'                    => new sfWidgetFormDate(),
      'activated_at'                  => new sfWidgetFormDate(),
      'period'                        => new sfWidgetFormInputText(),
      'interest_rate'                 => new sfWidgetFormInputText(),
      'amount'                        => new sfWidgetFormInputText(),
      'name'                          => new sfWidgetFormInputText(),
      'closed_at'                     => new sfWidgetFormDate(),
      'note'                          => new sfWidgetFormTextarea(),
      'currency_code'                 => new sfWidgetFormPropelChoice(array('model' => 'Currency', 'add_empty' => false)),
      'first_settlement_date'         => new sfWidgetFormDate(),
      'capitalize'                    => new sfWidgetFormInputCheckbox(),
      'contract_type_id'              => new sfWidgetFormPropelChoice(array('model' => 'ContractType', 'add_empty' => false)),
      'document'                      => new sfWidgetFormInputText(),
      'contract_excluded_report_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'Report')),
    ));

    $this->setValidators(array(
      'id'                            => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'creditor_id'                   => new sfValidatorPropelChoice(array('model' => 'Creditor', 'column' => 'id')),
      'created_at'                    => new sfValidatorDate(),
      'activated_at'                  => new sfValidatorDate(array('required' => false)),
      'period'                        => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
      'interest_rate'                 => new sfValidatorNumber(),
      'amount'                        => new sfValidatorNumber(),
      'name'                          => new sfValidatorString(array('max_length' => 255)),
      'closed_at'                     => new sfValidatorDate(array('required' => false)),
      'note'                          => new sfValidatorString(array('required' => false)),
      'currency_code'                 => new sfValidatorPropelChoice(array('model' => 'Currency', 'column' => 'code')),
      'first_settlement_date'         => new sfValidatorDate(array('required' => false)),
      'capitalize'                    => new sfValidatorBoolean(),
      'contract_type_id'              => new sfValidatorPropelChoice(array('model' => 'ContractType', 'column' => 'id')),
      'document'                      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'contract_excluded_report_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'Report', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('contract[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Contract';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['contract_excluded_report_list']))
    {
      $values = array();
      foreach ($this->object->getContractExcludedReports() as $obj)
      {
        $values[] = $obj->getReportCode();
      }

      $this->setDefault('contract_excluded_report_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveContractExcludedReportList($con);
  }

  public function saveContractExcludedReportList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['contract_excluded_report_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(ContractExcludedReportPeer::CONTRACT_ID, $this->object->getPrimaryKey());
    ContractExcludedReportPeer::doDelete($c, $con);

    $values = $this->getValue('contract_excluded_report_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new ContractExcludedReport();
        $obj->setContractId($this->object->getPrimaryKey());
        $obj->setReportCode($value);
        $obj->save();
      }
    }
  }

}
