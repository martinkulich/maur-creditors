<?php

/**
 * Report form base class.
 *
 * @method Report getObject() Returns the current form's model object
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseReportForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'code'                          => new sfWidgetFormInputHidden(),
      'name'                          => new sfWidgetFormInputText(),
      'contract_excluded_report_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'Contract')),
    ));

    $this->setValidators(array(
      'code'                          => new sfValidatorChoice(array('choices' => array($this->getObject()->getCode()), 'empty_value' => $this->getObject()->getCode(), 'required' => false)),
      'name'                          => new sfValidatorString(array('max_length' => 255)),
      'contract_excluded_report_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'Contract', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('report[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Report';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['contract_excluded_report_list']))
    {
      $values = array();
      foreach ($this->object->getContractExcludedReports() as $obj)
      {
        $values[] = $obj->getContractId();
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
    $c->add(ContractExcludedReportPeer::REPORT_CODE, $this->object->getPrimaryKey());
    ContractExcludedReportPeer::doDelete($c, $con);

    $values = $this->getValue('contract_excluded_report_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new ContractExcludedReport();
        $obj->setReportCode($this->object->getPrimaryKey());
        $obj->setContractId($value);
        $obj->save();
      }
    }
  }

}
