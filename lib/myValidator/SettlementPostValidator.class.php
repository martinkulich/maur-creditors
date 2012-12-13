<?php

class SettlementPostValidator extends sfValidatorBase
{

    public function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);

        $this->addMessage('paid_outgoing_payment_id_error', 'Outgoing payment required if paid is set');
        $this->addMessage('outgoing_payment_id_paid_error', 'Paid required if outgoing payment is set');
        $this->addMessage('paid_limit_exceeded_error', 'Paid exceeded unused amount %unused_amount%');
        $this->addMessage('no_unused_amount_error', 'There is no unused amount to pay');
        $this->addMessage('currency_mishmash', 'Currency of outgoing payment is not equal with cuurency of settlement contract');
        $this->addMessage('creditor_mishmash', 'Creditor of outgoing payment is not equal with creditor of settlement contract');
        
        
    }

    public function doClean($values)
    {
        $errorSchema = new sfValidatorErrorSchema($this);

        $paid = array_key_exists('paid', $values) ? $values['paid'] : null;
        $outgoingPaymentId = array_key_exists('outgoing_payment_id', $values) ? $values['outgoing_payment_id'] : null;
        if (($paid && !$outgoingPaymentId)) {
            $error = new sfValidatorError($this, 'paid_outgoing_payment_id_error');
            $errorSchema->addError($error, 'outgoing_payment_id');
            throw $errorSchema;
        }

        if ((!$paid && $outgoingPaymentId)) {
            $error = new sfValidatorError($this, 'outgoing_payment_id_paid_error');
            $errorSchema->addError($error, 'outgoing_payment_id');
            throw $errorSchema;
        }

        if ($outgoingPaymentId) {
            $outgoingPayment = OutgoingPaymentPeer::retrieveByPK($outgoingPaymentId);
            if ($outgoingPayment) {
                if (isset($values['contract_id'])) {
                    $contract = ContractPeer::retrieveByPK($values['contract_id']);
                    if($contract && $contract->getCurrencyCode() != $outgoingPayment->getCurrencyCode())
                    {
                        $error = new sfValidatorError($this, 'currency_mishmash');
                        $errorSchema->addError($error, 'outgoing_payment_id');
                        throw $errorSchema;
                    }
                    
                    if($contract && $contract->getCreditorId() != $outgoingPayment->getCreditorId())
                    {
                        $error = new sfValidatorError($this, 'creditor_mishmash');
                        $errorSchema->addError($error, 'outgoing_payment_id');
                        throw $errorSchema;
                    }
                }

                if ($paid) {
                    $criteria = new Criteria();
                    if (isset($values['id'])) {
                        $criteria->add(SettlementPeer::ID, $values['id'], Criteria::NOT_EQUAL);
                    }
                    $settlements = $outgoingPayment->getSettlements($criteria);

                    $UnusedAmount = $outgoingPayment->getAmount();
                    foreach ($settlements as $settlement) {
                        $UnusedAmount -= $settlement->getPaid();
                    }

                    if ($UnusedAmount < $paid) {
                        $errorName = $UnusedAmount <= 0 ? 'no_unused_amount_error' : 'paid_limit_exceeded_error';
                        $error = new sfValidatorError($this, $errorName, array('unused_amount' => $UnusedAmount));
                        $errorSchema->addError($error, 'paid');
                        throw $errorSchema;
                    }
                }
            }
        }
        if ($errorSchema->count() > 0) {

            throw $errorSchema;
        }
        return $values;
    }

}

