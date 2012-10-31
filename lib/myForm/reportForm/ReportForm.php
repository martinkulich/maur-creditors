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
            ));
        $this->setValidators(array(
            'date_from' => new myValidatorDate(array('required' => false)),
            'date_to' => new myValidatorDate(array('required' => false)),
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
