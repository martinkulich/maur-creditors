<?php

class PaymentService
{
    const PAYMENT = 'payment';
    const REACTIONVATION = 'reactivation';

    public function getPaymentTypeChoices($withEmpty = true)
    {
        $paymentTypes = array(
            self::PAYMENT,
            self::REACTIONVATION,
        );
        $translateService = ServiceContainer::getTranslateService();
        $choices = array();
        if ($withEmpty) {
            $choices[''] = '';
        }

        foreach ($paymentTypes as $paymentType) {
            $choices[$paymentType] = $translateService->__($paymentType);
        }

        return $choices;
    }
}
