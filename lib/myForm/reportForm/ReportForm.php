<?php

class ReportForm extends BaseForm
{

    public function configure()
    {
        $this->disableCSRFProtection();
        sfProjectConfiguration::getActive()->loadHelpers('Url');


        $this->setWidgets(array(
            'date_from' => new myJQueryDateWidget(),
            'date_to' => new myJQueryDateWidget(),
            'creditor_id'=> new sfWidgetFormPropelChoice(array('model'=>'creditor', 'order_by'=>  array('Lastname', 'asc'), 'add_empty'=>true)),
            'month'=> new myWidgetFormChoiceMonth(),
            'year'=> new sfWidgetFormPropelChoice(array('model' => 'RegulationYear')),
            ));
        $this->setValidators(array(
            'date_from' => new myValidatorDate(array('required' => false)),
            'date_to' => new myValidatorDate(array('required' => false)),
            'creditor_id'=> new sfValidatorPropelChoice(array('model'=>'creditor','required'=> false)),
            'month'=> new sfValidatorChoice(array('choices'=>$this->getWidget('month')->getChoicesKeys(), )),
            'year'=> new sfValidatorPropelChoice(array('model' => 'RegulationYear')),
        ));

        $usedFields = $this->getUsedFields();
        foreach ($this->getWidgetSchema()->getFields() as $field => $widget) {
            if (!in_array($field, $usedFields)) {
                $this->unsetField($field);
            }
        }
        $this->widgetSchema->setNameFormat('report[%s]');
    }

    public function getName()
    {
        return 'report';
    }

    public function getDefaults()
    {
        return array(
            'date_from' => new DateTime('now'),
        );
    }

    public function getUsedFields()
    {
        $usedFields = array();
        foreach ($this->getWidgetSchema()->getFields() as $field => $widget) {
            $usedFields[] = $field;
        }

        return $usedFields;
    }
}
