<?php

function my_format_currency($amount, $currency = null, $culture = null) {
    if (null === $amount) {
        return null;
    }

    if(is_null($currency))
    {
        $currency = my_default_currency_code();
    }
    $numberFormat = new sfNumberFormat(_current_language($culture));
    $amount = round($amount, 0);
    return $numberFormat->format($amount, '#,### ¤', $currency);
}

function my_format_converted_currency($amount, $baseCurrency, $newCurrency = null, $culture = null) {

    
    $currencyService = ServiceContainer::getCurrencyService();
    if(is_null($newCurrency))
    {
        $newCurrency = my_default_currency_code();
    }
        
    $numberFormat = new sfNumberFormat(_current_language($culture));
    $amount = $currencyService->recalculate($amount, $baseCurrency, $newCurrency);
    return my_format_currency($amount, $newCurrency, $culture);
}

function my_default_currency_code()
{
    return ServiceContainer::getCurrencyService()->getDefaultCurrency()->getCode();
}