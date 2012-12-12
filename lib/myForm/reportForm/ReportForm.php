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
            'year'=> new myWidgetFormChoiceYear(),
            ));
        $this->setValidators(array(
            'date_from' => new myValidatorDate(),
            'date_to' => new myValidatorDate(),
            'creditor_id'=> new sfValidatorPropelChoice(array('model'=>'creditor','required'=> false)),
            'month'=> new sfValidatorChoice(array('choices'=>$this->getWidget('month')->getChoicesKeys())),
            'year'=> new sfValidatorChoice(array('choices'=>$this->getWidget('year')->getChoicesKeys())),
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
