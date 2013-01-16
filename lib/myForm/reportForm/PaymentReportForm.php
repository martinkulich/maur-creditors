<?php

class PaymentReportForm extends ParentReportForm
{
    public function configure()
    {
        parent::configure();

        $paymemtTypeChoices = ServiceContainer::getPaymentService()->getPaymentTypeChoices();
        $this->setWidget('payment_type', new sfWidgetFormChoice(array('choices'=>$paymemtTypeChoices)));
        $this->setValidator('payment_type', new sfValidatorChoice(array('choices'=>  array_keys($paymemtTypeChoices), 'required'=>false)));

        $this->setWidget('bank_account_id', new sfWidgetFormPropelChoice(array('model' => 'BankAccount', 'add_empty' => true)));
        $this->setValidator('bank_account_id', new sfValidatorPropelChoice(array('required' => false, 'model' => 'BankAccount', 'column' => 'id')));

        $this->setWidget('sender_bank_account', new sfWidgetFormInput());
        $this->setValidator('sender_bank_account', new sfValidatorString(array('required' => false)));
    }

    public function getUsedFields()
    {
        return array(
            'date_from',
            'date_to',
            'creditor_id',
            'contract_id',
        );
    }
}
