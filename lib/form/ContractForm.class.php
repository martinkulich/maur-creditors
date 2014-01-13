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
        $this->getWidgetSchema()->moveField('currency_code', sfWidgetFormSchema::AFTER, 'amount');
        $this->getWidgetSchema()->moveField('first_settlement_date', sfWidgetFormSchema::AFTER, 'created_at');
        $dateFields = array(
            'activated_at',
            'created_at',
            'closed_at',
            'first_settlement_date',
        );

        foreach ($dateFields as $dateField) {
            $this->setWidget($dateField, new myJQueryDateWidget());
            $this->setValidator($dateField, new myValidatorDate());
        }

        $this->setWidget('document', new sfWidgetFormInputFile());
        $path = ServiceContainer::getDocumentService()->getDocumentRootDirPath();

        $this->setValidator('document', new sfValidatorFile(array('path' => $path, 'required' => false)));

        $this->getWidget('created_at')->setLabel('Date of signature');
        $this->getValidator('created_at')->setOption('last_day_in_month', 31);

        $this->getWidget('activated_at')->setLabel('Date of payment');
        $this->getValidator('activated_at')->setOption('required', false);

        $this->getValidator('closed_at')->setOption('required', false);

        $this->getValidator('first_settlement_date')->setOption('required', false);
        $this->getWidgetSchema()->setHelp('first_settlement_date', 'This field is unchangeable after contract activation');

        $this->getWidgetSchema()
            ->setHelp('capitalize', 'Newly generated settlements will be automaticaly capitalized')
            ->moveField('capitalize', sfWidgetFormSchema::FIRST);

        $this->getWidget('contract_excluded_report_list')->setOption('expanded', true);

        $periodChoices = Contract::getPeriods();
        $this->setWidget('period', new sfWidgetFormChoice(array('choices' => $periodChoices), array('class' => 'span2')));
        $this->setValidator('period', new sfValidatorChoice(array('choices' => array_keys($periodChoices), 'required' => true)));
        $this->getWidgetSchema()->setDefault('period', 2);

        $this->setWidget('interest_rate', new myWidgetFormInputPercentage());
        $this->getValidator('interest_rate')->setOption('min', 0);

        $this->getValidator('amount')->setOption('min', 0);
        $this->changeFieldToMyNumberField('amount');

        $this->getWidget('creditor_id')->setOption('order_by', array('Lastname', 'asc'));

        $fieldsToUnset = array(
            'activated_at',
            'closed_at',
        );

        if (!$this->getObject()->isNew()) {
            $fieldsToUnset[] = 'period';
        }

        if ($this->getObject()->getActivatedAt()) {
            $fieldsToUnset[] = 'first_settlement_date';
        }

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }
    }

    public function doSave($con = null)
    {
        $contract = $this->getObject();
        parent::doSave($con);
        $contract->reload();
        ServiceContainer::getContractService()->checkContractChanges($contract);
    }

}
