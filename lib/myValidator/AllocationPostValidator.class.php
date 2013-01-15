<?php

class AllocationPostValidator extends sfValidatorBase
{

    public function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);

        $this->addMessage('unallocated_limit_exceeded_error', 'Exceeded unallocated amount %unallocated_amount%');
        $this->addMessage('no_unallocated_amount_error', 'There is no amount to allocate');


    }

    public function doClean($values)
    {
        $errorSchema = new sfValidatorErrorSchema($this);

        $outgoingPaymentId = array_key_exists('outgoing_payment_id', $values) ? $values['outgoing_payment_id'] : null;
        if ($outgoingPaymentId) {

            $paid = array_key_exists('paid', $values) ? $values['paid'] : null;
            $balanceReduction = array_key_exists('balance_reduction', $values) ? $values['balance_reduction'] : null;
            $allocated = $paid + $balanceReduction;

            $outgoingPayment = OutgoingPaymentPeer::retrieveByPK($outgoingPaymentId);
            if ($outgoingPayment) {


                if ($allocated) {


                    $unallocated = $outgoingPayment->getUnallocatedAmount();
                    if (isset($values['id'])) {
                        $allocation = AllocationPeer::retrieveByPK($values['id']);
                        if ($allocation) {
                            $unallocated += $allocation->getAllocated();
                        }
                    }

                    if ($unallocated < $allocated) {
                        $errorName = $unallocated <= 0 ? 'no_unallocated_amount_error' : 'unallocated_limit_exceeded_error';
                        $error = new sfValidatorError($this, $errorName, array('unallocated_amount' => $unallocated));
                        if($paid)
                        {
                        $errorSchema->addError($error, 'paid');
                        }
                        if($balanceReduction)
                        {
                            $errorSchema->addError($error, 'balance_reduction');
                        }
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

