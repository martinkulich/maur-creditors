<?php

/**
 * Project form base class.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseFormPropel extends sfFormPropel
{

    public function setup()
    {
    }

    public function unsetField($field)
    {
        unset($this->validatorSchema[$field]);
        unset($this->widgetSchema[$field]);
    }
    
    public function changeField($fieldName, $widgetClass, $validatorClass)
    {
        $Widget = $this->getWidget($fieldName);
        $validator = $this->getValidator($fieldName);
        if (!$Widget) {
            throw new exception(sprintf('Widget %s does not exists', $fieldName));
        } elseif (!$validator) {
            throw new exception(sprintf('Validator %s does not exists', $fieldName));
        }
        
        $this->setWidget($fieldName, new $widgetClass($Widget->getOptions(), $Widget->getAttributes()));
        $this->setValidator($fieldName, new $validatorClass($validator->getOptions(), $validator->getMessages()));
    }

    public function changeFieldToMyNumberField($fieldName)
    {
        $this->changeField($fieldName, 'myWidgetFormInputNumber', 'myValidatorNumber');
    }
    public function getParentFormName()
    {
        return null;
    }

    public function getFullFormName()
    {
        $fullFormName = $this->getName();
        if ($parentFormName = $this->getParentFormName()) {
            $fullFormName = $parentFormName . '_' . $fullFormName;
        }

        return $fullFormName;
    }

}
