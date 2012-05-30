<?php

/**
 * Contract form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class ContractForm extends BaseContractForm
{

    public function configure()
    {
        $this->getWidgetSchema()->moveField('name', sfWidgetFormSchema::FIRST);
        $dateFields = array(
            'activated_at',
            'created_at',
            'closed_at',
        );

        foreach ($dateFields as $dateField) {
            $this->setWidget($dateField, new myJQueryDateWidget());
            $this->setValidator($dateField, new myValidatorDate());
        }

        $this->getWidget('created_at')->setLabel('Date of signature');
        $this->getWidget('activated_at')->setLabel('Date of payment');
        $this->getValidator('activated_at')->setOption('required', false);
        $this->getValidator('closed_at')->setOption('required', false);

        $periodChoices = Contract::getPeriods();
        $this->setWidget('period', new sfWidgetFormChoice(array('choices' => $periodChoices), array('class' => 'span2')));
        $this->setValidator('period', new sfValidatorChoice(array('choices' => array_keys($periodChoices), 'required' => true)));

        $this->setWidget('interest_rate', new myWidgetFormInputPercentage());
        $this->setWidget('amount', new myWidgetFormInputAmount(array(), array('class' => 'span2')));

        $this->getValidator('interest_rate')->setOption('min', 0);
        $this->getValidator('amount')->setOption('min', 0);

        $fieldsToUnset = array();

        if(!$this->getObject()->isNew())
        {
            $fieldsToUnset[] = 'period';
        }

        if($this->getObject()->getActivatedAt())
        {
            $fieldsToUnset[] = 'activated_at';
        }

        foreach($fieldsToUnset as $field)
        {
            $this->unsetField($field);
        }
    }

    public function doSave($con = null)
    {
        parent::doSave($con);

        ServiceContainer::getContractService()->updateContractSettlements($this->getObject());
    }
}
