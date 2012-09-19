<?php

class ClosingSettlementForm extends SettlementForm
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $calculateFirstDateWidget = $this->getWidget('calculate_first_date');
        $attributes = array();
        if ($calculateFirstDateWidget) {
            $attributes = $calculateFirstDateWidget->getAttributes();
        }
        $this->setWidget('calculate_first_date', new sfWidgetFormInputCheckbox(array(), $attributes));

        $this->getWidget('calculate_first_date')->setLabel('Reuse as reactivation');
        $this->getWidgetSchema()->moveField('calculate_first_date', sfWidgetFormSchema::FIRST);

        $fieldsToUnset = array(
            'creditor_id',
            'capitalized',
        );

        $this->getWidget('paid')->setLabel('To pay');

        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }


        $this->setWidget('contract_id', new sfWidgetFormInputHidden());

        $contract = $this->getObject()->getContract();
        $closingAmount = ServiceContainer::getContractService()->getContractClosingAmount($contract, null, $this->getValue('calculate_first_date'));
        $lastDate = $contract->getLastSettlementDate();
        $this->getObject()->setPaid($closingAmount['unsettled']);
        $this->getObject()->setBalanceReduction($closingAmount['balance_reduction']);
        $this->getWidget('date')->setLabel('Settlment Date');

        $calculateFirstDateOnChange = $dateOnChange = sprintf("calculateContractClosingAmount('#contract_closing_settlement_date_date','#contract_closing_settlement_paid','#contract_closing_settlement_balance_reduction','#contract_closing_settlement_calculate_first_date', '%s'); ", url_for('@contract_closing_amount?id=' . $contract->getId()));

        $dateParentOnChange = $this->getWidget('date')->getAttribute('onChange');
        if ($dateParentOnChange) {
            $dateOnChange .= $dateParentOnChange;
        }
        $this->getWidget('date')->setAttribute('onChange', $dateOnChange);


        $calculateFirstDateParentOnChange = $this->getWidget('calculate_first_date')->getAttribute('onChange');
        if ($calculateFirstDateParentOnChange) {
            $calculateFirstDateOnChange .= $calculateFirstDateParentOnChange;
        }

        $this->getWidget('calculate_first_date')->setAttribute('onChange', $calculateFirstDateOnChange);

//        $this->getWidgetSchema()->setHelp('date', 'Datum použité k výpočtu úroků.<br />Při rekativaci smlouvy nastavit na datum aktivace nové smlouvy.');
        $this->getWidgetSchema()->setHelp('date_of_payment', 'Datum odchazí platby.');
    }

    public function getParentFormName()
    {
        return 'contract';
    }

    public function getName()
    {
        return 'closing_settlement';
    }
}