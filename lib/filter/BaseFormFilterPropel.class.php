<?php

/**
 * Project filter form base class.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseFormFilterPropel extends sfFormFilterPropel
{

    public function setup()
    {
        $this->disableCSRFProtection();
        $this->disableEmptyCheckbox();
        $this->convertDateFields();
    }

    protected function disableEmptyCheckbox()
    {
        foreach ($this->getWidgetSchema()->getFields() as $field => $widget) {
            if ($widget->hasOption('with_empty')) {
                $widget->setOption('with_empty', false);
            }
        }
    }

    protected function convertDateFields()
    {
        foreach ($this->getWidgetSchema()->getFields() as $field => $widget) {
            if ($widget instanceof sfWidgetFormFilterDate) {
                $this->setWidget($field, new MyJQueryFormFilterDate());

                $validator = $this->getValidator($field);
                $validatorOptions = $validator->getOptions();
                $validatorMessages = $validator->getMessages();
                $this->setValidator($field, new MyValidatorDateRange($validatorOptions, $validatorMessages));
            }
        }
    }

    public function unsetField($field)
    {
        unset($this->validatorSchema[$field]);
        unset($this->widgetSchema[$field]);
    }

    public function setYesNoField($fieldName, $required=false)
    {
        $yesNoWidget = new myWidgetFormChoiceYesNo();
        $yesNoChoices = $yesNoWidget->getChoices();
        $this->setWidget($fieldName, $yesNoWidget);
        $this->setValidator($fieldName, new sfValidatorChoice(array('choices' => array_keys($yesNoChoices), 'required'=>$required)));
    }
}
