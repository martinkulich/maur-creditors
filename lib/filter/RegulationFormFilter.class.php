<?php

/**
 * Regulation filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class RegulationFormFilter extends BaseRegulationFormFilter
{
  public function configure()
  {
      $fieldsToUnset = array(
          'contract_name',
      );

      foreach($fieldsToUnset as $field)
      {
          $this->unsetField($field);
      }

      $this->setWidget('contract_id', new sfWidgetFormPropelChoice(array('add_empty'=>true,'model'=>'Contract', 'order_by'=>array('Name', 'asc'))));
      $this->setValidator('contract_id', new sfValidatorPropelChoice(array('model'=>'Contract', 'required'=>false)));
      $this->getWidgetSchema()->moveField('contract_id', sfWidgetFormSchema::FIRST);
  }

  public function addContractIdColumnCriteria(Criteria $criteria, $field, $value)
  {
      $criteria->add(RegulationPeer::CONTRACT_ID, $value);
      return $criteria;
  }
}
