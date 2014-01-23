<?php

/**
 * OutgoingPayment form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class OutgoingPaymentForm extends BaseOutgoingPaymentForm
{

    public function configure()
    {
        $this->getWidgetSchema()->moveField('creditor_id', sfWidgetFormSchema::FIRST);
        $this->getWidgetSchema()->moveField('refundation', sfWidgetFormSchema::AFTER, 'amount');
        $this->getWidgetSchema()->moveField('receiver_bank_account', sfWidgetFormSchema::AFTER, 'bank_account_id');
        $this->setWidget('date', new myJQueryDateWidget());
        $this->setValidator('date', new myValidatorDate());

        $this->getWidget('creditor_id')->setOption('order_by', array('Lastname', 'asc'));
        $this->getWidget('bank_account_id')->setOption('order_by', array('Name', 'desc'));

        $fields = array('amount', 'refundation');
        foreach ($fields as $field) {
            if (!$this->getObject()->isNew()) {
                $this->setWidget($field, new myWidgetFormInputAmount(array('currency_code' => $this->getObject()->getCurrencyCode())));
                $this->setValidator($field, new myValidatorNumber());
            } else {
                $this->changeFieldToMyNumberField($field);
            }
        }


    }

}
