<?php

/**
 * Currency form.
 *
 * @package    rezervuj
 * @subpackage form
 * @author     Your name here
 */
class CurrencyForm extends BaseCurrencyForm {

    public function configure() {
        $this->unsetField('is_default');
    }

    public function doSave($con = null) {
        parent::doSave($con);

        $criteria = new Criteria();
        $criteria->add(CurrencyPeer::IS_DEFAULT, true);
        $defaultCurrencies = CurrencyPeer::doSelect($criteria);
        $defaultCurrenciesCount = count($defaultCurrencies);
        if (0 == $defaultCurrenciesCount) {
            $this->getObject()->setIsDefault(true);
            $this->getObject()->setRate(1);
            $this->getObject()->save($con);
        } elseif ($defaultCurrenciesCount > 1) {
            $setDefaultCurrency = true;
            foreach ($defaultCurrencies as $currency) {
                if ($this->getObject()->getIsDefault()) {

                    if ($currency->getCode() != $this->getObject()->getCode()) {
                        $currency->setIsDefault(false);
                        $currency->save($con);
                    }
                } else {
                    if ($setDefaultCurrency) {
                        $setDefaultCurrency = false;
                    } else {
                        $currency->setIsDefault(false);
                        $currency->save($con);
                    }
                }
            }
        }

        if ($this->getObject()->getIsDefault()) {
            $this->getObject()->setRate(1);
            $this->getObject()->save($con);
        }
    }

}

