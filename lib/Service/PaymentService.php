<?php

class PaymentService
{
    const PAYMENT = 'payment';
    const REACTIVATION = 'reactivation';
    const BALANCE_INCREASE = 'balance_increase';

    public function getPaymentTypeChoices($withEmpty = true)
    {
        $paymentTypes = array(
            self::PAYMENT,
            self::REACTIVATION,
            self::BALANCE_INCREASE,
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
