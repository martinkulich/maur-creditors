<?php

/**
 * Subject form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class SubjectForm extends BaseSubjectForm
{
  public function configure()
  {
      $translateService = ServiceContainer::getTranslateService();
      $typeChoices = Subject::getSubjectTypes();

      $this->setWidget('subject_type_code', new sfWidgetFormChoice(array('choices' => $typeChoices)));
      $this->setValidator('subject_type_code', new sfValidatorChoice(array('choices' => array_keys($typeChoices), 'required' => true)));

      $this->setWidget('birth_date', new myJQueryDateWidget());
      $this->setValidator('birth_date', new myValidatorDate(array('required'=>false)));

  }
}
