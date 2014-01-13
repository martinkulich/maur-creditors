<?php

class ParentReportForm extends BaseForm
{

    public function configure()
    {
        $this->disableCSRFProtection();
        sfProjectConfiguration::getActive()->loadHelpers('Url');


        $this->setWidgets(array(
            'date_from' => new myJQueryDateWidget(),
            'date_to' => new myJQueryDateWidget(),
            'creditor_id'=> new sfWidgetFormPropelChoice(array('model'=>'creditor', 'order_by'=>  array('Lastname', 'asc'), 'add_empty'=>'All')),
            'contract_id'=> new sfWidgetFormPropelChoice(array('add_empty' => true, 'model' => 'Contract', 'order_by' => array('Name', 'asc'))),
            'contract_type_id'=> new sfWidgetFormPropelChoice(array('add_empty' => true, 'model' => 'ContractType', 'order_by' => array('Name', 'asc'))),
            'month'=> new myWidgetFormChoiceMonth(),
            'year'=> new myWidgetFormChoiceYear(),
            'years'=> new myWidgetFormChoiceYear(array('multiple'=>true, 'expanded'=>true)),
            'currency_code' => new sfWidgetFormPropelChoice(array('add_empty' => false, 'model' => 'Currency', 'order_by' => array('Code', 'asc'))),
            ));
        $this->setValidators(array(
            'date_from' => new myValidatorDate(),
            'date_to' => new myValidatorDate(),
            'creditor_id'=> new sfValidatorPropelChoice(array('model'=>'creditor','required'=> false)),
            'contract_id'=> new sfValidatorPropelChoice(array('model' => 'Contract', 'required' => false)),
            'contract_type_id'=> new sfValidatorPropelChoice(array('model' => 'ContractType', 'required' => false)),
            'month'=> new sfValidatorChoice(array('choices'=>$this->getWidget('month')->getChoicesKeys())),
            'year'=> new sfValidatorChoice(array('choices'=>$this->getWidget('year')->getChoicesKeys())),
            'years'=> new sfValidatorChoice(array('choices'=>$this->getWidget('year')->getChoicesKeys(), 'multiple'=>true, 'required' => false)),
            'currency_code'=> new sfValidatorPropelChoice(array('model' => 'Currency', 'required' => false)),
        ));

        
        $this->getWidget('creditor_id')->setAttribute('onchange', sprintf("updateSelectBox('%s','%s','%s', '%s', 'all'); ;", url_for('@update_contract_select?form_name=report'), 'report_creditor_id', 'report_contract_id', 'creditor_id'));

        $contract = ContractPeer::retrieveByPK($this->getValue('contract_id'));
        if ($contract) {
            $this->getWidgetSchema()->setDefault('creditor_id', $contract->getCreditorId());
        }
        
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
