<?php

class CurrencyService
{

    protected $defaultCurrency = null;

    /**
     * @return Currency
     */
    public function getDefaultCurrency()
    {
        if (is_null($this->defaultCurrency)) {
            $criteria = new Criteria();
            $criteria->add(CurrencyPeer::CODE, sfConfig::get('app_default_currency_code'));
            $this->defaultCurrency = CurrencyPeer::doSelectOne($criteria);
        }

        return $this->defaultCurrency;
    }

}
