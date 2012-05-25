<?php

/**
 * Creditor form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class CreditorForm extends BaseCreditorForm
{
  public function configure()
  {
      $translateService = ServiceContainer::getTranslateService();
      $typeChoices = Creditor::getCreditorTypes();
      
      $this->setWidget('creditor_type_code', new sfWidgetFormChoice(array('choices'=>$typeChoices)));
      $this->setValidator('creditor_type_code', new sfValidatorChoice(array('choices'=>array_keys($typeChoices), 'required'=>true)));
  }
}
