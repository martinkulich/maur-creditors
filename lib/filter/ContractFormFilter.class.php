<?php

/**
 * Contract filter form.
 *
 * @package    rezervuj
 * @subpackage filter
 * @author     Your name here
 */
class ContractFormFilter extends BaseContractFormFilter
{

    public function configure()
    {
        $this->getWidgetSchema()->moveField('name', sfWidgetFormSchema::FIRST);
        
        $this->setWidget('created_at', new MyJQueryFormFilterDate());
        $this->setWidget('activated_at', new MyJQueryFormFilterDate());

        $this->setValidator('created_at', new MyValidatorDateRange(array('required' => false)));
        $this->setValidator('activated_at', new MyValidatorDateRange(array('required' => false)));

        $periodChoices = Contract::getPeriods();
        $this->setWidget('period', new sfWidgetFormChoice(array('choices' => $periodChoices), array('class' => 'span2')));
        $this->setValidator('period', new sfValidatorChoice(array('choices' => array_keys($periodChoices), 'required' => true)));
    }
}
