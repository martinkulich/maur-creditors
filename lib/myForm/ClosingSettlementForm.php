<?php

class ClosingSettlementForm extends SettlementForm
{

    public function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $translateService = ServiceContainer::getTranslateService();
        $settlementTypeChoices = array(
            SettlementPeer::CLOSING => $translateService->__('Without reactivation'),
            SettlementPeer::CLOSING_BY_REACTIVATION => $translateService->__('With reactivation'),
        );
        $this->setWidget('settlement_type', new sfWidgetFormChoice(array('choices' => $settlementTypeChoices)));
        $this->setValidator('settlement_type', new sfValidatorChoice(array('choices' => array_keys($settlementTypeChoices))));
        $this->getWidgetSchema()->moveField('settlement_type', sfWidgetFormSchema::FIRST);
        $this->getWidget('settlement_type')->setLabel('Closing type');

        $fieldsToUnset = array(
            'creditor_id',
            'capitalized',
            'contract_id'
        );


        foreach ($fieldsToUnset as $field) {
            $this->unsetField($field);
        }


        $contract = $this->getObject()->getContract();
        $closingAmount = ServiceContainer::getContractService()->getContractClosingAmount($contract);
        $lastDate = $contract->getLastSettlementDate();
        $this->getWidget('date')->setLabel('Closed at');

        $dateOnChange = sprintf("calculateContractClosingAmount('#settlement_date_date','#settlement_paid','#settlement_balance_reduction','#settlement_settlement_type', '%s'); ", url_for('@contract_closing_amount?id=' . $contract->getId()));

        $dateParentOnChange = $this->getWidget('date')->getAttribute('onChange');
        if ($dateParentOnChange) {
            $dateOnChange .= $dateParentOnChange;
        }


        $this->getWidget('date')->setAttribute('onChange', $dateOnChange);
        $this->getWidget('settlement_type')->setAttribute('onChange', $dateOnChange);

//        $this->getWidgetSchema()->setHelp('date', 'Datum použité k výpočtu úroků.<br />Při rekativaci smlouvy nastavit na datum aktivace nové smlouvy.');
    }

    public function doSave($con = null)
    {
        parent::doSave($con);
        $settlement = $this->getObject();
        $contract = $settlement->getContract();
        $contract->setClosedAt($settlement->getDate());

        $contract->save($con);

        if($settlement->getSettlementType() == SettlementPeer::CLOSING_BY_REACTIVATION && $contract->getCapitalize())
        {
            $settlement->setCapitalized($contract->getUnsettled(new DateTime($settlement->getDate())));
            $settlement->save($con);
        }

        ServiceContainer::getContractService()->checkContractChanges($contract);
    }
}