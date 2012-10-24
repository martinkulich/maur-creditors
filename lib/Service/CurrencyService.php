<?php

class CurrencyService {

    protected $defaultCurrency = null;
    protected $currencies = null;

    /**
     * @return Currency
     */
    public function getDefaultCurrency() {
        if (is_null($this->defaultCurrency)) {
            $criteria = new Criteria();
            $criteria->add(CurrencyPeer::IS_DEFAULT, true);
            $this->defaultCurrency = CurrencyPeer::doSelectOne($criteria);
            if (!$this->defaultCurrency) {
                throw new exception('Undefined default currency');
            }
        }

        return $this->defaultCurrency;
    }

    public function recalculate($amount, $baseCurrencyCode, $newCurrencyCode = null) {
        return (float) $amount * $this->getCurrencyRate($baseCurrencyCode, $newCurrencyCode);
    }

    public function getCurrencyRate($baseCurrencyCode, $newCurrencyCode = null) {
        $baseCurrency = $this->getCurrency($baseCurrencyCode);

        $newCurrencyCode = is_null($newCurrencyCode) ? $this->getDefaultCurrency()->getCode() : $newCurrencyCode;
        $newCurrency = $this->getCurrency($newCurrencyCode);

        return (float) $baseCurrency->getRate() / (float) $newCurrency->getRate();
    }

    protected function initCurrencies() {
        $this->currencies = array();
        foreach (CurrencyPeer::doSelect(new Criteria()) as $currency) {
            $this->currencies[$currency->getCode()] = $currency;
        }
    }

    /**
     * 
     * @param string $currencyCode
     * @return Currency $currency
     * @throws exception
     */
    protected function getCurrency($currencyCode) {
        if (is_null($this->currencies)) {
            $this->initCurrencies();
        }

        if (!array_key_exists($currencyCode, $this->currencies)) {
            throw new exception('Unknown curency code ' . $currencyCode);
        }

        return $this->currencies[$currencyCode];
    }

}
